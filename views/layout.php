<!DOCTYPE html>
<html>
<head>
<!--    <link rel="stylesheet" type="text/css" href="/css/mystyle.css">-->
    <style>
        table {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
            font-size: 14px;
            border-collapse: collapse;
            text-align: left;
            margin: 5px;
        }

        .calendar table {
            font-size: 11px;
            text-align: center;
        }

        .calendar th {
            background: #AFCDE7;
            color: white;
            padding: 10px 20px;
        }

        th, td {
            border-style: solid;
            border-width: 0 1px 1px 0;
            border-color: white;
        }

        .calendar td {
            background: #D8E6F3;
            padding: 10px 20px;
            height: 100px;
            width: 100px;
            vertical-align: top;

        }

        .calendar .tableContent div {
            position: absolute;
            display: block;
            left: 0px;
            top: 0px;
            color: #0A01F5;
            vertical-align: 50px;
        }

        .calendar div.appList {
            position: relative;
            display: block;
            left: 0px;
            top: 0px;
            color: #0A55F5;
        }

        .calendar .appList a {
            position: relative;
            display: block;
            text-decoration: none;
            color: #0A55F5;
            font-size: smaller;
        }

        a.boardrooms {
            text-decoration: underline;
            color: #00aff0;
            padding: 10px;
            vertical-align: text-bottom;
            border-radius: 10px;

        }

        a.boardrooms:hover {
            text-decoration: none;
            transition: color 0.5s;
            background-color: #52828f;
            color: #a8e4fb;
        }

        .sideMenu li {
            list-style-type: none;
            padding: 15px 0;
            font-size: 1.5em;
        }

        a.homeUrl {
            background-color: #AFCDE7;
        }

        div.brdrmLst {
            text-align: center;
            background-color: #80AFC9;
            height: 20px;
            padding: 15px;
            min-width: 300px !important;

        }

        .brdrmLst a {
            background-color: #AFCDE7;
            overflow: hidden;
        }

        div.headline {

            float: left;
            margin-left: 15px;
            text-align: center;
        }

        div.curBrdrm {
            background-color: #7dadc8;
            float: left;
            text-align: left;
            width: 100px;
            border-radius: 15px;

            margin-top: 10px;

            margin-left: 100px;
            padding-left: 50px;

        }

        div.tableCal {
            float: right;
            /*position: static;*/
            display: inline-table;
        }

        .modal-container {
            position: fixed;
            background-color: #fff;
            left: 50%;
            width: 70%;
            max-width: 400px;

            padding: 20px;
            border-radius: 5px;

            -webkit-transform: translate(-50%, -300%);
            -ms-transform: translate(-50%, -300%);
            transform: translate(-50%, -300%);

            -webkit-transition: transform 200ms ease-out;
            -moz-transition: transform 200ms ease-out;
            -ms-transition: transform 200ms ease-out;
            -o-transition: transform 200ms ease-out;
            transition: transform 200ms ease-out;
        transform(- 50 %, 300 %);
        }

        .modal:target:before {
            display: block;

        }

        .modal:before {
            content: '';
            position: fixed;
            display: none;

            background-color: rgba(0, 0, 0, 0.5);
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;

        }

        .modal:target .modal-container {
            top: 20%;
            -moz-transform: translate(-50%, 0);
            -webkit-transform: translate(-50%, 0);
            -ms-transform: translate(-50%, 0);
            transform: translate(-50%, 0);
        }

        #modal-close {
        }
<!--    </style>-->
</head>
<body><?php require_once('routes.php'); ?></body>
</html>
