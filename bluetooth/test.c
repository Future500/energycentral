/* tool to read power production data for SMA solar power convertors
Copyright Wim Hofman 2010
Copyright Stephen Collier 2010,2011

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>. */

/* compile gcc -lbluetooth -g -o smatool smatool.c */

#define _XOPEN_SOURCE /* glibc needs this */
#include <stdio.h>
#include <stdlib.h>
#include <stdarg.h>

int debug = 2;

/*
* Log function, supports levels.
* Any log message of equal or lower level than
* the current verbosity level is printed.
*
*  0       Emergency: system is unusable
*  1       Alert: action must be taken immediately
*  2       Critical: critical conditions
*  3       Error: error conditions
*  4       Warning: warning conditions
*  5       Notice: normal but significant condition
*  6       Informational: informational messages
*  7       Debug: debug-level messages
*/
void
d( char *format, int const lvl, int verbosity, ...)
{
    va_list l;
    char str[256];

    va_start(l, verbosity);
    vsprintf(str, format, l);
    va_end(l);

    if (lvl <= verbosity) {
        printf("%s\n",str);
    }
}

int middle()
{
    d( "test string %s %s %i", 0, debug, "middle", "test", debug );
}

int main(int argc, char **argv)
{
    int i=0;

    for (i=1;i<argc;i++) {          //Read through passed arguments
        if ((strcmp(argv[i],"-d")==0)||(strcmp(argv[i],"--debug")==0)) {
            i++;
            if(i<argc){
                debug=atoi(argv[i]);
            }
        }
    }

    middle();
    d( "test string %s %s %i", 1, debug, "test1", "test2", debug );
}
