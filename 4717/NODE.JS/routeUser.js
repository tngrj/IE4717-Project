"use strict";

const db = require('../server');

class UserDB {
    
    //Login
    login(request, respond) {
        var email = request.body.email;
        var password = request.body.password;

        var sql = "SELECT * FROM 4717database.user WHERE email = ?";

        db.query(sql, [email], function (error, result) {
            if (error) {
                throw error;
            } else {
                if (result.length > 0) {
                    if (password == result[0].password) {
                        respond.json(result); 
                    } else {
                        respond.status(401).json({ message: "Invalid password" }); 
                    }
                } else {
                    respond.status(401).json({ message: "Invalid email" }); 
                }
            }
        });
    }

    //Add User
    addUser(request, respond) {

        var addemail = request.body.addemail;
        var addpassword = request.body.addpassword;
        var addlastname = request.body.addlastname;
        var addfirstname = request.body.addfirstname;
        var addbirthday = request.body.addbirthday;
        var addusertype = "user";

        var sql = "INSERT INTO 4717database.user (email, password, lastname, firstname, birthday, usertype) VALUES (?,?,?,?,?,?)";
        var values = [addemail, addpassword, addlastname, addfirstname, addbirthday, addusertype];
        
        db.query(sql, values, function (error, result) {
            if (error) {
                if (error.code === "ER_DUP_ENTRY") {
                    respond.json({ message: "Duplicate Entry" });
                }
            }
            else {
                respond.json({ message: "Added Successfully" });
            }
        });
    }

    //User - Main: Display Appointments
    getUserMain(request, respond) {

        var userId = request.params.userId;

        var sql = "SELECT * FROM 4717database.doctor_schedule WHERE patient = (?)";

        db.query(sql,  userId, function (error, result) {
            if (error) {
                throw error;
            }
            else {
                respond.json(result);
            }
        });
    }

    //User - Main: Add Appointments
    adduNewAppt(request, respond) {

        var doctorname = request.body.doctorname;
        var appttype = request.body.appttype;
        var apptdate = request.body.apptdate;
        var appttime = request.body.appttime;
        var apptcomment = request.body.apptcomment;
        var patientname = request.body.patientname;

        var sql = "INSERT INTO 4717database.doctor_schedule (doctor, date, time, patient, appointmentType, comment) VALUES (?,?,?,?,?,?)";
        var values = [doctorname, apptdate, appttime, patientname, appttype, apptcomment];
        
        console.log(values);
        db.query(sql, values, function (error, result) {
            if (error) {
                throw error;
            }
            else {
                respond.json({ message: "Form is Submitted. Please close the page." });
            }
        });
    }

}



function routeUser(app) {

    var userDBObject = new UserDB();

    //Login
    app.route('/login')
        .post(userDBObject.login);

    //Add User
    app.route('/addUser')
        .post(userDBObject.addUser);

    //User - Main: Get Appointments
    app.route('/uMainAppt/:userId')
        .get(userDBObject.getUserMain);

    //User - Main: Add Appointments
    app.route('/adduNewAppt')
        .post(userDBObject.adduNewAppt);
    
}



module.exports = {
    UserDB: UserDB,
    routeUser: routeUser
};