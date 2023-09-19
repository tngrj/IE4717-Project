"use strict";  
var mysql = require('mysql');
var connection = mysql.createPool({
    host:'localhost',
    port:'3304',
    user:'root',
    password:'',
    database:'4717database'
});
module.exports = connection;


const express = require("express");
const bodyParser = require("body-parser");
const routeUser = require('./NODE.JS/routeUser');

var app = express();
var host = "127.0.0.1";
var port = 3000;
var startPage = "LandingPage.html";   

app.use(express.static("./HTML"));     
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

routeUser.routeUser(app);


//?
function gotoIndex(req, res) {
    console.log(req.params);
    res.sendFile(__dirname + "/" + startPage);
}
app.get("/" + startPage, gotoIndex);

app.route("/");
//


var server = app.listen(port, host, function() {
    var host = server.address().address;
    var port = server.address().port;
    console.log("Server started on http://%s:%s/LandingPage.html", host, port);}
); 