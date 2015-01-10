/*
 * Utils.h
 *
 *  Created on: 21 Feb 2010
 *      Author: Dave
 */

#ifndef UTILS_H_
#define UTILS_H_
#include <iostream>
#include <iomanip>
#include <sstream>
#include <sys/time.h>
#include <ctime>
#include <cmath>
#ifdef _WIN32
#include <windows.h>
#endif

namespace vortexlib {

class Timer {
	timeval tStart, tEnd;
	timeval marks[100];
	int markCount;

public:
	Timer();
	virtual ~Timer();

	void start();
	void mark();
	void stop();
	std::string printTime( timeval t );
	std::string print();

	timeval dif( timeval v1, timeval v2 );
};

namespace utils {

void sleep( int milisec );

inline int round(float r) {
    return (int)( (r > 0.0) ? std::floor(r + 0.5) : std::ceil(r - 0.5) );
}

inline float hypotenuse( const int x, const int y ) {
	return sqrt( pow(x, 2) + pow(y, 2) );
}

}
}

#endif /* UTILS_H_ */
