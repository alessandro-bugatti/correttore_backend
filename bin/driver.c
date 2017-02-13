/* 

Driver program for checking IOI-like programs 1.9

Copyright (C) 2001-2003 Paolo Boldi and Sebastiano Vigna 

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2, or (at your option) any
later version.
	
This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.
	
You should have received a copy of the GNU General Public License along
with this program; see the file COPYING.  If not, write to the Free
Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
02111-1307, USA.



This program takes as input an executable program name, and tests the given
program with respect to a number of test cases.

Note that to perform the test, unless stdio is used each input file will be in
turn hard-linked to the filename required by the program. Thus, it is wise to
write-protect input files.

If you do not use a comparator, the program does not need any external
file/device or program. Thus, it can be compiled statically and safely used in
a chroot'd environment.

If you use a comparator, /bin/sh must be a shell (statically linked, if you
plan to chroot) that will be used by popen(). You can use, for instance, sash.

TEST CASES
==========

Each test case is defined by one or more input files, and by one output
file. The names of the input files and output file are provided by means of
filename formats: each format is a filename pattern, with possibly one "%d" in
it, that is substituted with 0, 1, 2 etc. to obtain the filenames of the
input/output files.


For example, suppose that the following input/output filename patterns are
specified:

	- input filename patterns: ROAD%d.IN, CAST.IN, TEST%d
	- output filename pattern: OUT%d.OUT

Then the first test case is given by the input files ROAD0.IN, CAST.IN, TEST0
and by the output file OUT0.OUT; the second test case is given by the input
files ROAD1.IN, CAST.IN, TEST1 and by the output file OUT1.OUT; and so on.

OPTION -i <input filename pattern>
	The option -i can be used to specify an input filename pattern. If more
	input filename patterns are needed, the option must be specified many times.
	If no -i option is present, then it is assumed that the only input filename
	pattern is input%d.txt.

OPTION -o <output filename pattern>
	The option -o can be used to specify the output filename pattern; this
	option can appear at most once. If no -o option is present, then it is
	assumed that the only output filename pattern is output%d.txt.
	
OPTION -n <number of test cases>
	The option -n can be used to specify the number of test cases; test cases
	are numbered from 0 to the number of test cases minus 1.  If no -n option is
	present, the number of test cases is assumed to be 10.

	
PERFORMING THE TEST
===================

The standard behaviour of the driver is as follows. For test case number k, the
input files of the test case are copied onto files whose name is obtained by
dropping the "%d" specification from the input filename pattern. Then the
executable is run; it is expected to produce an output file whose name is
obtained by dropping the "%d" specification from the output file pattern.

In the example above, at the first test case, the files ROAD0.IN, CAST.IN,
TEST0 are copied onto ROAD.IN, CAST.IN, TEST (respectively) and the executable
is run. It should produce a file named OUT.OUT.

OPTION -s
	This option can be specified only if there is exactly one input filename
	pattern.  If this option is specified, the executable is expected to read
	from its standard input and to produce output on its standard output. Thus,
	the driver will feed the executable with the input file corresponding to the
	current test case, and will copy the standard output of the executable onto
	the required file.
	
OPTION -t <number of seconds>

	This option specifies the number of seconds of user time the executable is
	allowed to run (per test case); if the executable exceeds the specified
	amount of time, the score for that test case is assumed to be 0. The amount
	of time may be a float, but it is senseless to specify values below
	getrusage() accuracy. Note that the program will be actually killed after
	this number of seconds (rounded up), unless the -T option is specified. This
	might be a problem for programs performing a large amount of I/O, as that
	time is not added to the user time, but it could be considered by the
	operating system in calculating the timeout. If you plan to check programs
	of this kind, please set the -T option to a higher number of
	seconds. Default: 10.
	
OPTION -T <number of seconds>
	This option specifies the number of seconds after which the program will be
	killed. Since this time might include the time spent during I/O, sometime it
	is wise to set it higher than -t (it causes no harm, in any case). Default:
	the value of the -t option rounded up.
	
OPTION -k <number of KiB>
OPTION -m <number of MiB>
	This option specifies the amount of memory the executable is allowed to use
	(per test case); if the executable uses more than that amount, it is killed
	and the score for that test case is assumed to be 0. Currently does not work
	under Linux. Default: none.
	

EVALUATING THE RESULT
=====================

After the executable has been run, the output obtained should be checked for
correctness. If no comparison program is specified (see below), the driver
simply compares the output file obtained with the reference output file (for that
test case); the comparison ignores the amount of leading and trailing
whitespace in each line, and empty lines at the end of the files. If the two
files are equal, the score assigned for that test case is 1; otherwise, it is
0.

If a comparison program is specified, the comparison program is run. The
comparison program receives the following arguments:
	- a list of one or more input filenames
	- the filename of the reference output
	- the filename of the output obtained.
The comparison program can write log messages on its standard error, but it is
expected to write on its own standard output one single floating-point number
between 0 and 1: this number is the score that is assigned to the test case.

OPTION -c <comparison program> 
	This option is used to specify that a comparison program should be used.
	

DRIVER OUTPUT
=============

The driver outputs the score obtained on each test case, and the final total
score (obtained by adding up the scores assigned to each test case).

OPTION -r <result filename pattern>
	If this option is specified, the output file obtained at each test case is
	copied on the specified file.

*/

#include <stdio.h>
#include <math.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <sys/stat.h>
#include <assert.h>
#include <errno.h>
#include <string.h>
#include <ctype.h>
#include <unistd.h>
#include <getopt.h>
#include <signal.h>
#include <sys/time.h>
#include <sys/resource.h>
                     
#define MAXINPUTFILES 16

#define BUFSIZE (1024*1024)

char buffer1[BUFSIZE];
char buffer2[BUFSIZE];  /* For built-in file comparison */
char *compname; /* The name of the comparator, if any */

int ninputfiles = 0;
char format[] = "%d", /* The format part */
	*inputfile[MAXINPUTFILES], /* The vector of ninputfiles input files (possibly containing the format string) */
	*outputfile = "output%d.txt",  /* The output file */
	*stdoutputfile, /* The output file without %d (i.e., what the program to be tested is expected to output) */
	*resultfile; /* An optional result file format for storing the output of each test. */



/* Copies the input files that contain format onto their
   i-instantiation. Returns -1 in case of I/O error. */

int copy(int i) {
	char b[1024], c[1024], *p;
	int k;

	for(k=0; k<ninputfiles; k++) {
		sprintf(b, inputfile[k], i);

		if (p = strstr(inputfile[k], format)) {
			strncpy(c, inputfile[k], p-inputfile[k]);
			strcpy(c + (p-inputfile[k]), p+strlen(format));

			unlink(c);
			if (link(b, c)) return -1;
		}
		else continue;
	}

	return 0;
}



/* 

	This function computes the score. If compname is NULL, the comparison is
	performed as follows: trailing and leading whitespace on each line, and
	trailing whitespace at the end of the file are not considered.

	If compname is not NULL, then the program compname is used for comparison.
	It should work as follows:
	
	compname <first input file name> <second input file name> ... <reference output file name> <user output file name>
	
	Then, the unique float printed by the comparison program on its standard
	output is returned.

	The input files are obtained instantiating inputfile[]; the reference files
	are obtained instantiating outputfile; and the user output file is
	stdoutputfile (which is outputfile without format).

*/

float compare(int i) {
	FILE *f1,*f2;
	char *p1, *p2;
	int error, c;	
	char filename[1024];

	if ( compname ) {
		int k;
		FILE *f;
		char b[1024];
		float result;

		sprintf(b, "%s ", compname);
		for(k=0; k<ninputfiles; k++) {
			sprintf(filename, inputfile[k], i);
			strcat(strcat(b, filename), " ");
		}

		sprintf(filename, outputfile, i);
		strcat(strcat(strcat(strcat(b, filename), " "), stdoutputfile), " ");

		f = popen( b, "r" );
		assert( f );

		if ( fgets(b, 1024, f) == NULL ) {
			fprintf(stderr, "Error or EOF from comparator pipe.\n");
			exit(1);
		}

		if (strlen(b) == 0) {
			fprintf(stderr, "Cannot read any data from comparator pipe.\n");
			exit(1);
		}

		if (sscanf(b, "%f", &result) != 1) {
			fprintf(stderr, "Cannot read value from comparator pipe (returned line in \"%s\").\n", b);
			exit(1);
		}
		return result;
	}

	sprintf(filename, outputfile, i);
	
	f1=fopen(filename, "r");
	if (!f1)  {
		fprintf(stderr, "Cannot open reference output file (%s).\n", filename);
		exit(1);
	}

	f2=fopen(stdoutputfile, "r");
	if (!f2) return 0;

	error=0; 
	while (!feof(f1) && !feof(f2)) {
		if (fgets(buffer1, BUFSIZE, f1) == NULL) buffer1[0] = 0;
		if (fgets(buffer2, BUFSIZE, f2) == NULL) buffer2[0] = 0;
		
		if (strlen(buffer1) == BUFSIZE || strlen(buffer2) == BUFSIZE) {
			fprintf(stderr, "This shouldn't happen: buffer size exceeded.\n");
			error = 1;
			break;
		}


		if (*buffer1) {
			p1 = buffer1 + strlen(buffer1);
			while(isspace(*--p1));
			*++p1 = 0;
		}

		if (*buffer2) {
			p2 = buffer2 + strlen(buffer2);
			while(isspace(*--p2));
			*++p2 = 0;
		}

		p1 = buffer1;
		p2 = buffer2;

		while(isspace(*p1)) p1++;
		while(isspace(*p2)) p2++;

		if (strcmp(p1, p2)) {
			error = 1;
			break;
		}
	}

	while((c = fgetc(f1)) != EOF) 
		if (!isspace(c)) {
			error = 1;
			break;
		}

	while((c = fgetc(f2)) != EOF) 
		if (!isspace(c)) {
			error = 1;
			break;
		}

	fclose(f1);
	fclose(f2);
	return !error;
}



/* Prints an error message on stderr and exits with error code 1. */

void error(char *msg) {
	fprintf(stderr, "%s\n", msg);
	exit(1);
}



int main(int argc,char *argv[]) {
	char *p, opt, b[1024], programname[1024], *dummy[2] = { NULL, NULL };
	int i, pid, 
		stdio = 0, /* If true, the executable uses stdio */
		n = 10; /* Number of test cases */
	float correct = 0, cr;
	pid_t child;
	int childstatus;
	struct rusage rusage;
	struct stat s;
	long prevms = 0, curms, 
		millisecs = 10*1000, /* Time limit */
		timeout = 0, /* Timeout */
		mem = 0; /* Memory limit in KiB */
	struct rlimit rlimit = { 0, 0 };
	/* This will be used for all logging operations. */
	FILE *orig_stderr = fdopen(dup(STDERR_FILENO), "w");

	if (argc<2) {
		fprintf(orig_stderr, 
				  "Usage: %s [options] executable\n"
				  "Options:\n"
				  "  -c <program>\t\t\tUse this comparator\n"
				  "  -n <number>\t\t\tNumber of test cases (default: 10)\n"
				  "  -t <seconds>\t\t\tUser time limit in seconds (may be a float; default: 10)\n"
				  "  -T <seconds>\t\t\tTimeout before signalling the process (may include system time; default: ceiled user time limit)\n"
				  "  -m <MiB>\t\t\tMemory size limit in MiB=2^20B (default: none)\n"
				  "  -k <KiB>\t\t\tMemory size limit in KiB=2^10B (default: none)\n"
				  "  -i <input filename format>\tDefault: input%%d.txt (more than one allowed)\n"
				  "  -o <output filename format>\tDefault: output%%d.txt\n"
				  "  -r <result filename format>\tDefault: none\n" 
				  "  -s\t\t\t\tExecutable uses stdio\n"
				  "\n"
				  "Note that not all systems implement memory usage limits.\n"
				  , argv[0]);
		return 1;
	}

	while( ( opt = getopt(argc, argv, "i:o:c:C:t:T:k:m:r:sn:") ) != -1 ) {
		switch(opt) {
		case 's': stdio = 1; break;
		case 't': if ((millisecs = (int)(atof(optarg)*1000)) <= 0) error("The number of seconds must be a positive number."); break;
		case 'T': if ((timeout = atoi(optarg)) <= 0) error("The timeout must be a positive integer."); break;
		case 'm': if (mem || (mem = atoi(optarg)) <= 0) error(mem <= 0 ? "The number of MiB must be a positive integer." : "Options -k and -m are not compatible"); mem*=1024; break;
		case 'k': if (mem || (mem = atoi(optarg)) <= 0) error(mem <= 0 ? "The number of KiB must be a positive integer." : "Options -k and -m are not compatible"); break;
		case 'n': if ((n = atoi(optarg)) <= 0) error("The number of test cases must be a positive integer."); break;
		case 'c': compname = optarg; break;
		case 'i': if (ninputfiles < MAXINPUTFILES) inputfile[ninputfiles++] = optarg;  else error("Too many input files."); break;
		case 'o': outputfile = optarg; break;
		case 'r': resultfile = optarg; break;
		case ':':
		case '?': return 1;
		}
	}

	if (!timeout) timeout = ceil(millisecs/1000.0);
	mem *= 1024;
	
	if (stdio && ninputfiles > 1) error("You cannot redirect from more than one file.");

	if (ninputfiles == 0) inputfile[ninputfiles++] = "input%d.txt";

	stdoutputfile = malloc(strlen(outputfile));
	if (p = strstr(outputfile, format)) {
		strncpy(stdoutputfile, outputfile, p-outputfile);
		strcpy(stdoutputfile + (p-outputfile), p+strlen(format));
	}
	else strcat(stdoutputfile, outputfile);

	if (optind >= argc) {
		fprintf(orig_stderr, "No executable specified.\n");
		exit(1);
	}

	signal(SIGPIPE, SIG_IGN); // Ignore signals from broken pipes.

	strcpy(programname, argv[optind]);
	dummy[0] = programname;

	for (i=0;i<n;i++) {
		if (!stdio && copy(i)) {
			fprintf(orig_stderr, "Error copying input/output files (step %d)!\n", i);
			exit(1);
		}

		unlink(stdoutputfile); /* Don't leave around old output files. */

		getrusage(RUSAGE_CHILDREN, &rusage);
		prevms = rusage.ru_utime.tv_sec*1000 + rusage.ru_utime.tv_usec/1000;

		switch (child=fork()) {
		case 0: {
		   /* child process */
		   fprintf(orig_stderr, n <= 10 ? "Executing on file n. %d\t" : "Executing on file n. %2d\t", i);
			fflush(orig_stderr);
  
		   /* First, we kill stderr. */
		   int e[2];
		   if (pipe(e)) fprintf(orig_stderr, "Error while creating stderr pipe.\n");
		   if (close(e[0])) fprintf(orig_stderr, "Error while blocking stderr pipe.\n");
		   dup2(e[1], STDERR_FILENO);
		   
		   /* We reopen stdio on the input/output files. */
			if (stdio) sprintf(b, inputfile[0], i);
		   
			if (!stdio || freopen(b, "r", stdin)) {
				if (stdio) sprintf(b, stdoutputfile, i);
			   
			   if (!stdio || freopen(b, "w", stdout)) {
			      
			      if (!stdio) {
						/* If stdio is not used, we create pipes so that all outputs is
							discarded and all input gives EOF (to avoid lockups). */
						int p[2], q[2];
						if (pipe(p) || pipe(q)) fprintf(orig_stderr, "Error while creating pipes.\n");
						if (close(p[1]) || close(q[0])) fprintf(orig_stderr, "Error while blocking pipes.\n");
						dup2(p[0], STDIN_FILENO);
						dup2(q[1], STDOUT_FILENO);
			      }
			      
			      rlimit.rlim_cur = timeout;
			      rlimit.rlim_max = timeout;
			      if (setrlimit(RLIMIT_CPU, &rlimit)) 
						fprintf(orig_stderr, "Cannot set CPU time limit: %s\n", strerror(errno));
			      if (mem) { /* Note that currently Linux does not implement memory usage limits */
						rlimit.rlim_cur = mem;
						rlimit.rlim_max = mem;
						if (setrlimit(RLIMIT_DATA, &rlimit)) 
							fprintf(orig_stderr, "Cannot set memory limit: %s\n", strerror(errno));
			      }
			      
			      execve(programname, dummy, NULL);
			      /* if here, an error occurred */
			      fprintf(orig_stderr, "Error while executing the child program. Is the executable ok?\n");
			   }
				else fprintf(orig_stderr, stdio ? "Error opening output file (%s) in child.\n" : "Error closing stdout in child.\n%*s", b);
			}
			else fprintf(orig_stderr, stdio ? "Error opening input (%s) in child.\n" : "Error closing stdin in child.\n%*s", b);
			kill(getpid(), SIGQUIT); /* So we can tell whether we have been killed by the kernel. */
			wait(NULL);
		}
		case -1: /* parent process, but fork was unsuccessful */
		   error("fork() was unsuccessful!\n");
		default: /* parent process */

			pid = wait4(child, &childstatus, 0, &rusage);
			assert(pid == child);

			curms = rusage.ru_utime.tv_sec*1000 + rusage.ru_utime.tv_usec/1000;

			if (WIFSIGNALED(childstatus) || (curms > millisecs) ) {
				if (WTERMSIG(childstatus) == SIGKILL || (curms > millisecs)) fprintf(orig_stderr, "Time out%s [user time: %6.3fs]\n", mem?" or allowed memory exceeded":"", curms/1000.0); /* Killed by the kernel. */
				else if (WTERMSIG(childstatus) == SIGQUIT) exit(1); /* Kill by ourselves--big problem. */
				else fprintf(orig_stderr, "Execution error (signal %d)!\n", WTERMSIG(childstatus)); /* SIGSEGV, etc. */
				continue;
			}

			fprintf(orig_stderr, "[user time: %6.3fs] ", curms/1000.0);
            fflush(orig_stderr); //Added by Alessandro Bugatti
			if ( stat( stdoutputfile, &s ) ) { /* No output file */
				fprintf(orig_stderr, "No output file\n");
			}
			else {
				if (resultfile) { /* If required, record output file. */
					sprintf(b, resultfile, i);
					unlink(b);
					link(stdoutputfile, b);
				}

				if ((cr = compare(i)) > 0) {
					fprintf(orig_stderr, "Success! (%.4f)\n", cr);
					fflush(orig_stderr); //Added by Alessandro Bugatti
					correct += cr;
				}
				else {
					fprintf(orig_stderr, "Output file is not correct\n");
					fflush(orig_stderr); //Added by Alessandro Bugatti
				}
			}
		}		
	}

	fprintf(orig_stderr, "Score: %f\n", correct);

	/* We now unlink everything we left behind. */

	unlink(stdoutputfile);

	for(i=0; i<ninputfiles; i++) {
		if (p = strstr(inputfile[i], format)) {
			strncpy(b, inputfile[i], p-inputfile[i]);
			strcpy(b + (p-inputfile[i]), p+strlen(format));
			unlink(b);
		}
	}

	return 0;
}
