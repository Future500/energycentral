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

/************************************************************************************************
	
	Calculate Sunrise/Sunset time for specific date at a given position
	Based on http://williams.best.vwh.net/sunrise_sunset_algorithm.htm

************************************************************************************************/


#ifdef WIN32
//Microsoft Visual Studio 2010 warnings
//warning C4996: 'fopen': This function or variable may be unsafe. Consider using fopen_s instead.
//To disable deprecation, use _CRT_SECURE_NO_WARNINGS. See online help for details.
#pragma warning(disable: 4996)
#endif

#include "sunrise_sunset.h"

time_t sunrise(float latitude, float longitude, time_t when)
{
    return sunrise_sunset(latitude, longitude, when, true);
}

time_t sunset(float latitude, float longitude, time_t when)
{
    return sunrise_sunset(latitude, longitude, when, false);
}

time_t sunrise_sunset(float latitude, float longitude, time_t when, int calcSunrise)
{
	//const float zenith = 90 + 50/60;
	const float zenith = 91;

    //1. first calculate the day of the year
	struct tm loctime_tm;
	memcpy(&loctime_tm, localtime(&when), sizeof(loctime_tm));
	struct tm utctime_tm;
	memcpy(&utctime_tm, gmtime(&when), sizeof(utctime_tm));

	float localOffset = (loctime_tm.tm_hour - utctime_tm.tm_hour) + (float)(loctime_tm.tm_min - utctime_tm.tm_min)/60;
	if ((loctime_tm.tm_year > utctime_tm.tm_year) || (loctime_tm.tm_mon > utctime_tm.tm_mon) || (loctime_tm.tm_mday > utctime_tm.tm_mday))
		localOffset += 24;
	if ((loctime_tm.tm_year < utctime_tm.tm_year) || (loctime_tm.tm_mon < utctime_tm.tm_mon) || (loctime_tm.tm_mday < utctime_tm.tm_mday))
		localOffset -= 24;

    //2. convert the longitude to hour value and calculate an approximate time
	float lngHour = longitude / 15;
	float t = loctime_tm.tm_yday + (((calcSunrise ? 6 : 18) - lngHour) / 24);

    //3. calculate the Sun's mean anomaly
	float M = 0.9856f * t - 3.289f;

    //4. calculate the Sun's true longitude
	float L = M + 1.916f * sin(dtr(M)) + 0.020f * sin(dtr(2*M)) + 282.634f;
	//NOTE: L potentially needs to be adjusted into the range [0,360] by adding/subtracting 360
	if (L > 360) L -= 360;
	if (L < 0) L += 360;

    //5a. calculate the Sun's right ascension
	float RA = rtd(atan(0.91764f * tan(dtr(L))));
	//NOTE: RA potentially needs to be adjusted into the range [0,360] by adding/subtracting 360
	if (RA > 360) RA -= 360;
	if (RA < 0) RA += 360;

    //5b. right ascension value needs to be in the same quadrant as L
	float Lquadrant  = floor(L/90) * 90;
	float RAquadrant = floor(RA/90) * 90;
	RA += (Lquadrant - RAquadrant);

    //5c. right ascension value needs to be converted into hours
	RA /= 15;

    //6. calculate the Sun's declination
	float sinDec = 0.39782f * sin(dtr(L));
	float cosDec = cos(asin(sinDec));

    //7a. calculate the Sun's local hour angle
	float cosH = (cos(dtr(zenith)) - (sinDec * sin(dtr(latitude)))) / (cosDec * cos(dtr(latitude)));

	if ((cosH >  1) || (cosH < -1))
	{
        //the sun never rises/sets on this location (on the specified date)
	    return 0;
	}

    //7b. finish calculating H and convert into hours
	  float H = rtd(acos(cosH));	//sunset
	  if (calcSunrise) H = 360 - H;	//sunrise
	  H /= 15;

    //8. calculate local mean time of rising/setting
	float T = H + RA - (0.06571f * t) - 6.622f;

    //9. adjust back to UTC
	float UT = T - lngHour;
	//NOTE: UT potentially needs to be adjusted into the range [0,24] by adding/subtracting 24
	if (UT > 24) UT -= 24;
	if (UT < 0) UT += 24;

    //10. convert UT value to local time zone of latitude/longitude
	float localT = UT + localOffset;
	loctime_tm.tm_hour = (int)localT;
	loctime_tm.tm_min = (int)((localT - loctime_tm.tm_hour) * 60);
	loctime_tm.tm_sec = 0;

	time_t loctime = mktime(&loctime_tm);
		
	return loctime;
}
