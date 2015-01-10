/*
 * world.cpp
 *
 *  Created on: 15 May 2010
 *      Author: Dave
 */

#include "world.h"

/*
 * worldwide constants
 */
#define GRID_X 1000
#define GRID_Y 1000
#define MAP_X 1000000
#define MAP_Y 1000000

using namespace tdv;
using namespace std;

World::World()
{
	PGresult *res;

	this->status = WORLD_OK;
	pthread_mutex_init( &this->incident_mutex, NULL );
	pthread_cond_init( &this->incident_cond, NULL );

	this->curMoves = new Move[5]; // *** placeholder (size? Vector?)
	this->grid = new int[((GRID_X * GRID_Y)*4)]; // *** placeholder
	this->incidents = new int[5];

	this->conninfo = "dbname=thedarkvortex user=thedarkvortex_server password=},(%B?l?*toitg3mNqM3i:Jq%>EY6x";
	this->conn = PQconnectdb( this->conninfo );
	if (PQstatus(this->conn) != CONNECTION_OK)
	{
		this->status = WORLD_NO_DB;
		this->error << "Connection to database has failed: " << PQerrorMessage(this->conn);
		PQfinish(this->conn);
		return;
	}

	res = PQexec( this->conn, "SET search_path TO simulation,public" );
	if( PQresultStatus(res) != PGRES_COMMAND_OK ) {
		this->status = WORLD_NO_DB;
		this->error << "Could not set search path" << PQerrorMessage(this->conn);
		PQclear(res);
		PQfinish(this->conn);
		return;
	}

}

World::~World()
{
	// TODO Auto-generated destructor stub
}

int World::getStatus()
{
	return this->status;
}

std::string World::getError()
{
	return this->error.str();
}

/*
 * create path object (calculate duration / other vals)
 *   add to path db
 * add to grid
 *   add grid entries to db
 * add to meetings
 *   add meetings to db
 * flag path in db as fully added
 */
int World::addMove( int group, int t1, int x2, int y2, int s )
{
	std::cout << "adding path for group " << group << " at " << t1 << "\n"
			<< "to " << x2 << "," << y2 << " at speed " << s << std::endl;
	// if new path is within current timeframe (starts soon enough) add to our list of current paths
	// , else just make temp one
	int x1 = x2
	  , y1 = y2;

	if (PQstatus(this->conn) != CONNECTION_OK)
	{
		fprintf(stderr, "Connection to database no good: %s",
				PQerrorMessage(this->conn));
		PQfinish(this->conn);
	}

	PGresult *res;
	int nFields, i, j;

	// *** vvv commented out so I don't make broken db calls while trying to figure out data encapsulation
//	const char *paramValues[4];
//	paramValues[0] = "2";
//	paramValues[1] = "2010-05-09 01:00:00";
//	paramValues[2] = "2010-05-09 02:00:00";
//	paramValues[3] = "((1,2),(3,4))";
//	cout << "params set" << endl;
//
//	res = PQexecParams( this->conn, "INSERT INTO movements (group_id, t_start, t_end, path) VALUES ($1, $2, $3, $4)",
//		4, NULL, paramValues, NULL, NULL, 0 );
////	res = PQexec( this->conn, "INSERT INTO experiments.movements VALUES ('1', '2010-05-09 01:00:00', '2010-05-09 01:00:00', '((1,2),(3,4))')");
//	cout << "query executed" << endl;
//	if( PQresultStatus(res) != PGRES_COMMAND_OK ) {
//		cout << "oh noes :-(" << endl;
//		cout << "INSERT command failed" << PQerrorMessage(this->conn) << endl;
//		PQclear(res);
//		PQfinish(this->conn);
//		return -2;
//	}
//	PQclear( res );
//
	// *** ^^^ uncomment this when comms are more stable


//	PQescapeStringConn escapes special characters
//	size_t PQescapeStringConn (PGconn *conn,
//	                                char *to, const char *from, size_t length,
//	                                int *error);

    /*
     * Fetch rows from pg_database, the system catalog of databases
     */
//    res = PQexec(conn, "DECLARE myportal CURSOR FOR  select * from experiments.movements;");
//    if (PQresultStatus(res) != PGRES_COMMAND_OK)
//    {
//        fprintf(stderr, "DECLARE CURSOR failed: %s", PQerrorMessage(conn));
//        PQclear(res);
//		PQfinish(conn);
//		return -2;
//    }
//    PQclear(res);
//
//    res = PQexec(conn, "FETCH ALL in myportal");
//    if (PQresultStatus(res) != PGRES_TUPLES_OK)
//    {
//        fprintf(stderr, "FETCH ALL failed: %s", PQerrorMessage(conn));
//        PQclear(res);
//		PQfinish(conn);
//		return -2;
//    }
//
//    /* first, print out the attribute names */
//    nFields = PQnfields(res);
//    for (i = 0; i < nFields; i++)
//        printf("%-15s", PQfname(res, i));
//    printf("\n\n");
//
//    /* next, print out the rows */
//    for (i = 0; i < PQntuples(res); i++)
//    {
//        for (j = 0; j < nFields; j++)
//            printf("%-15s", PQgetvalue(res, i, j));
//        printf("\n");
//    }
//
//    PQclear(res);
//
//    /* close the portal ... we don't bother to check for errors ... */
//    res = PQexec(conn, "CLOSE myportal");
//    PQclear(res);
//
//	res = PQexec( conn, "END" );
//	PQclear( res );



	Move m( x1, y1, x2, y2, t1, s );

	// find starting point based on unit / time
	// crate new path (flag as incomplete)
	// curtail old path at that point

	return 0;
}

int World::simIncidents()
{
	cout << "-sim pulse" << endl;
	return 1;
}

