/*
 * comms.h
 *  Created on: 01 May 2010
 *      Author: Dave
 *     Credits: Edward Alston Anthony - http://devhood.com/tools/tool_details.aspx?tool_id=413
 *              UNIX Network Programming (W. Richard Stevens)
 *  References: http://beej.us/guide/bgnet/output/html/multipage/index.html (very heavily... thanks Beej)
 */

#include <string>
#include <cstring>
#include <iostream>
#include <sstream>

#include <sys/socket.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <sys/wait.h>
#include <netdb.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <errno.h>
#include <signal.h>


#ifndef _SOCKET_WRAPPER_H_
#define _SOCKET_WRAPPER_H_

#define MAXDATASIZE 100 // max number of bytes we can get in a single message
#define BACKLOG 10     // how many pending connections queue will hold


/**
 * This class represents a single network communication line (1-1)
 * It may be an addressed socket or a listener to a local port
 * If we're listening to a local port we may create new instances to deal
 * with incoming connections
 */
class Comms
{
private:
    int sockfd;

public:
    struct sockaddr_storage *sa;
	Comms() {}
	~Comms() { this->cDisconnect(); }

	std::string cGetInAddr();
	int cConnect( const char *host, const char *port, const bool l = false );
	void cDisconnect();
	int cAccept( Comms &newCom );
	int cSend( const std::string &message );
	int cRecv( std::string &reply );

	// to be removed
	int getSockfd();
};

/**
 * Get the socket file descriptor used by this comm line.
 * Only useful while not all functionality implimented in this object
 */
int Comms::getSockfd()
{
	return this->sockfd;
}

// get sockaddr, IPv4 or IPv6:
std::string Comms::cGetInAddr()
{
	std::stringstream out;
    int maxlen = INET6_ADDRSTRLEN;
	char s[maxlen];
	struct sockaddr *sa = (struct sockaddr *)this->sa;

	switch(sa->sa_family) {
	case AF_INET:
		out << inet_ntop(AF_INET, &(((struct sockaddr_in *)sa)->sin_addr), s, maxlen);
		break;

	case AF_INET6:
		out << inet_ntop(AF_INET6, &(((struct sockaddr_in6 *)sa)->sin6_addr), s, maxlen);
		break;

	default:
		out << "Unknown AF : " << sa->sa_family;
	}

	return out.str();
}

/**
 * @param host "localhost" // The host client will be connecting to
 * @param port "3490" // the port client will be connecting to
 * @param Optional parameter "l" determines if we're just connecting, or binding and listening
 */
int Comms::cConnect( const char *host, const char *port, const bool l )
{
	struct addrinfo hints, *servinfo, *p;
    int rv, yes=1;

    memset(&hints, 0, sizeof hints);
    hints.ai_family = AF_UNSPEC;
    hints.ai_socktype = SOCK_STREAM;
    if( l ) {
    	hints.ai_flags = AI_PASSIVE; // use my IP if we're listening
    }

    if ((rv = getaddrinfo( (l ? NULL : host), port, &hints, &servinfo)) != 0) {
        fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
        return 1;
    }

    // loop through all the results and connect or bind to the first we can
    for(p = servinfo; p != NULL; p = p->ai_next) {
        if ((this->sockfd = socket(p->ai_family, p->ai_socktype,
                p->ai_protocol)) == -1) {
            perror("client: socket");
            continue;
        }

        if( l ) {
            if (setsockopt(this->sockfd, SOL_SOCKET, SO_REUSEADDR, &yes,
                    sizeof(int)) == -1) {
                perror("setsockopt");
                exit(1);
            }

            if (bind(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
                close(this->sockfd);
                perror("problem when binding");
                continue;
            }
        }
        else {
			if (connect(this->sockfd, p->ai_addr, p->ai_addrlen) == -1) {
				close(this->sockfd);
				perror("problem when connecting");
				continue;
			}
        }

        break;
    }

    // give some feedback if things went wrong
    if (p == NULL) {
    	if( l ) {
            fprintf(stderr, "failed to bind\n");
    	}
    	else {
    		fprintf(stderr, "failed to connect\n");
    	}
        return 2;
    }

    // store the address info of our socket
    this->sa = (struct sockaddr_storage *)p->ai_addr;

    freeaddrinfo(servinfo); // all done with this structure

    if( l ) {
    	if (listen(this->sockfd, BACKLOG) == -1) {
    		perror("listen");
    		exit(1);
    	}
    }

    return 0;
}

void Comms::cDisconnect()
{
	close(this->sockfd);
}

/**
 * Accept incoming connection on our passive socket
 * and set the given Comms instance to communicate on the new active socket
 */
int Comms::cAccept( Comms &newCom )
{
	socklen_t sin_size = sizeof *(newCom.sa);
	newCom.sa = new sockaddr_storage();
	newCom.sockfd = accept( this->sockfd, (struct sockaddr *)newCom.sa, &sin_size );

	if( newCom.sockfd == -1 ) {
		return -1;
	}
	return 0;
}

int Comms::cSend( const std::string &message )
{
	int rv = send(this->sockfd, message.c_str(), (message.size() + 1), 0);
	return rv;
}

int Comms::cRecv( std::string &reply )
{
	int numbytes;
    char buf[MAXDATASIZE];
    numbytes = recv(this->sockfd, buf, MAXDATASIZE-1, 0);
    if (numbytes == -1) {
		perror("recv");
		return -1;
	}

	reply.assign( buf, numbytes ); // put what we were given into a string
	return numbytes;
}

#endif /* _SOCKET_WRAPPER_H_ */
