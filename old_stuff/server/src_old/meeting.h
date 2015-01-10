/*
 * Meeting.h
 *
 *  Created on: 1 Mar 2010
 *      Author: Dave
 */

#ifndef MEETING_H_
#define MEETING_H_
#include <iostream>
#include <sstream>

namespace vortexlib {

//class Path;

class Meeting {
	int tStart, tEnd;
//	Path *p1, *p2;
	int p1id, p2id;

public:
	Meeting();
	virtual ~Meeting();

//	void init( const int &tStart, const int &tEnd, const Path &p1, const Path &p2);
	void init( const int &tStart, const int &tEnd, const int &p1, const int &p2);

	std::string toString();
};

}

#endif /* MEETING_H_ */
