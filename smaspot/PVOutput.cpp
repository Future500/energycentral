/************************************************************************************************
	SMAspot - Yet another tool to read power production of SMA solar inverters
	(c)2012-2013, SBF (mailto:s.b.f@skynet.be)

	Latest version found at http://code.google.com/p/sma-spot/

	License: Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
	http://creativecommons.org/licenses/by-nc-sa/3.0/

	You are free:
		to Share — to copy, distribute and transmit the work
		to Remix — to adapt the work
	Under the following conditions:
	Attribution:
		You must attribute the work in the manner specified by the author or licensor
		(but not in any way that suggests that they endorse you or your use of the work).
	Noncommercial:
		You may not use this work for commercial purposes.
	Share Alike:
		If you alter, transform, or build upon this work, you may distribute the resulting work
		only under the same or similar license to this one.

DISCLAIMER:
	A user of SMAspot software acknowledges that he or she is receiving this
	software on an "as is" basis and the user is not relying on the accuracy
	or functionality of the software for any purpose. The user further
	acknowledges that any use of this software will be at his own risk
	and the copyright owner accepts no responsibility whatsoever arising from
	the use or application of the software.

************************************************************************************************/

/***********************************************************************************************
 **                                 _   _ ____  _
 **                             ___| | | |  _ \| |
 **                            / __| | | | |_) | |
 **                           | (__| |_| |  _ <| |___
 **                            \___|\___/|_| \_\_____|
 **
 ** Curl is a command line tool for transferring data specified with URL
 ** syntax. Find out how to use curl by reading the curl.1 man page or the
 ** MANUAL document. Find out how to install Curl by reading the INSTALL
 ** document.
 **
 ** COPYRIGHT AND PERMISSION NOTICE
 **
 ** Copyright (c) 1996 - 2008, Daniel Stenberg, <daniel@haxx.se>.
 **
 ** All rights reserved.
 **
 ** Permission to use, copy, modify, and distribute this software for any purpose
 ** with or without fee is hereby granted, provided that the above copyright
 ** notice and this permission notice appear in all copies.
 **
 ** THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 ** IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 ** FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. IN
 ** NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 ** DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 ** OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
 ** OR OTHER DEALINGS IN THE SOFTWARE.
 **
 ** Except as contained in this notice, the name of a copyright holder shall not
 ** be used in advertising or otherwise to promote the sale, use or other dealings
 ** in this Software without prior written authorization of the copyright holder.
 **
 ***********************************************************************************************/

/***********************************************************************************************
 ** Configuration for Windows build (Visual Studio 2010)
 ** Visit http://curl.haxx.se/download.html
 **	Download latest development version for Windows (libcurl-7.18.0-win32-msvc.zip)
 **	Extract libcurl.lib to %ProgramFiles%\Microsoft Visual Studio 10.0\VC\lib
 ** Extract include\curl\*.* to %ProgramFiles%\Microsoft Visual Studio 10.0\VC\include\curl
 ** Extract libcurl.dll to %windir%\system32 or Release/Debug folder
 ** curl needs zlib1.dll - See http://sourceforge.net/projects/gnuwin32/files/zlib/1.2.3/
 ***********************************************************************************************/

/***********************************************************************************************
 ** Configuration for Linux build (Ubuntu)
 ** sudo apt-get install curl libcurl3 libcurl4-nss-dev
 ***********************************************************************************************/

#include "PVOutput.h"

int PVOutputExport(Config *cfg, InverterData *invdata)
{
	CURL *curl;
	CURLcode res = CURLE_OK;
	char postData[512];
	const char *dt_format = "d=%Y%m%d&t=%H:%M";
	const char *addStatus = "http://pvoutput.org/service/r2/addstatus.jsp";

	if (VERBOSE_NORMAL) puts("PVOutputExport()");

	//Upload highest value of Grid Voltage (Fixes issue 23)
	long UacMax = max(max(invdata->Uac1, invdata->Uac2), invdata->Uac3);

	/* In windows, this will init the winsock stuff */
	curl_global_init(CURL_GLOBAL_ALL);

	curl = curl_easy_init();
	if (curl)
	{
	    //curl_easy_setopt(curl, CURLOPT_URL, addStatus);
		//snprintf(postData, sizeof(postData), "data=%s,%lld,%ld,,,,%.0f,1&sid=%d&key=%s", strftime_t(dt_format, invdata->InverterDatetime), invdata->EToday, invdata->TotalPac, (float)UacMax/100, cfg->PVoutput_SID, cfg->PVoutput_Key);
		snprintf(postData, sizeof(postData), "%s?%s&v1=%lld&v2=%ld&v6=%.1f&c1=1&sid=%d&key=%s", addStatus, strftime_t(dt_format, invdata->InverterDatetime), invdata->EToday, invdata->TotalPac, (float)UacMax/100, cfg->PVoutput_SID, cfg->PVoutput_Key);
	    curl_easy_setopt(curl, CURLOPT_URL, postData);
		//curl_easy_setopt(curl, CURLOPT_POSTFIELDS, postData);
		res = curl_easy_perform(curl);
		if(res != CURLE_OK)
			printf("curl_easy_perform() failed: %s\n", curl_easy_strerror(res));

		curl_easy_cleanup(curl);
	}

	curl_global_cleanup();

	return res;
}
