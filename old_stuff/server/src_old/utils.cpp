/*
 * Utils.cpp
 *
 *  Created on: 21 Feb 2010
 *      Author: Dave
 */

#include "Utils.h"

namespace vortexlib {

Timer::Timer() {
	this->markCount = -1;
}

Timer::~Timer() {

}

void Timer::start() {
	gettimeofday( &this->tStart, 0 );
}

void Timer::mark() {
	this->markCount++;
	gettimeofday( &this->marks[this->markCount], 0 );
}

void Timer::stop() {
	gettimeofday( &this->tEnd, 0 );
}

std::string Timer::print() {
	using namespace std;
	std::stringstream out;
	timeval dif, total;
	total = this->dif( this->tStart, this->tEnd );

	out << "start : " << this->printTime( this->tStart ) << std::endl;
	if( this->markCount > 0 ) {
		dif = this->dif( this->tStart, this->marks[0] );
		out << "mark 0: " << this->printTime( this->marks[0] ) << " , gap: " << this->printTime( dif ) << std::endl;
		for( int i = 1; i <= this->markCount; i++) {
			out << "mark " << i << ": " << this->printTime( this->marks[i] );
			dif = this->dif( this->marks[(i-1)], this->marks[i] );
			out << " , gap: " << this->printTime( dif ) << std::endl;
		}
		dif = this->dif( this->marks[this->markCount], this->tEnd );
		out << "end   : " << this->printTime( this->tEnd ) << " , gap: " << this->printTime( dif ) << std::endl;
	}
	else {
		out << "end   : " << this->printTime( this->tEnd ) << std::endl;
	}
	out << "total : " << this->printTime( total ) << std::endl;

	return out.str();
}

std::string Timer::printTime( timeval t ) {
	std::string rstr;
	std::stringstream out;

	out << t.tv_sec << ".";
	if(      t.tv_usec < 10 ) { out << "00000"; }
	else if( t.tv_usec < 100 ) { out << "0000"; }
	else if( t.tv_usec < 1000 ) { out << "000"; }
	else if( t.tv_usec < 10000 ) { out << "00"; }
	else if( t.tv_usec < 100000 ) { out << "0"; }
	out << t.tv_usec;
	return out.str();
}

timeval Timer::dif( timeval v1, timeval v2 ) {
	timeval r;
	if( v2.tv_usec < v1.tv_usec ) {
		r.tv_usec = (v2.tv_usec + 1000000) - v1.tv_usec;
		r.tv_sec  = (v2.tv_sec - 1) - v1.tv_sec;
	}
	else {
		r.tv_usec = v2.tv_usec - v1.tv_usec;
		r.tv_sec  = v2.tv_sec  - v1.tv_sec;
	}
	return r;
}

namespace utils {

void sleep( int milisec ) {
	#ifdef _WIN32
	   Sleep(milisec);
	#else
	   struct timespec timeOut,remains;
	   timeOut.tv_sec = milisec / 1000;
	   timeOut.tv_nsec = (milisec % 1000) * 1000000; //
	   nanosleep(&timeOut, &remains);
	#endif
}

}
}
