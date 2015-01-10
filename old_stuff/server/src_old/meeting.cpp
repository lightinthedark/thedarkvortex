/*
 * Meeting.cpp
 *
 *  Created on: 1 Mar 2010
 *      Author: Dave
 */

#include "Meeting.h"

namespace vortexlib {

Meeting::Meeting() {
	this->tStart = 0;
	this->tEnd = 0;
}

Meeting::~Meeting() {
}

/*
void Meeting::init( const int &tStart, const int &tEnd, const Path &p1, const Path &p2) {
	this->tStart = tStart;
	this->tEnd = tEnd;
	this->p1 = &p1;
	this->p2 = &p2;
}
// */

void Meeting::init( const int &tStart, const int &tEnd, const int &p1, const int &p2) {
	this->tStart = tStart;
	this->tEnd = tEnd;
	this->p1id = p1;
	this->p2id = p2;
}

std::string Meeting::toString() {
	std::stringstream out;
	out << "Meeting: " << this->tStart << " to " << this->tEnd << std::endl;
	out << this->p1id << std::endl;
	out << this->p2id << std::endl;
//	out << this->p1.toString() << std::endl;
//	out << this->p2.toString() << std::endl;
	return out.str();
}

}
