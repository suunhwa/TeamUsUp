<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUsUp - Edit Group</title>
    <link rel="stylesheet" href="/css/manageGroup2.css"> 
    
</head> 
<?php include "sidebar.php"?>
<body>
    <div class="container">
        <h2>Manage Group</h2>
        <div class="cgrp-box">
            <form class = "cgrp">
                <ul>
                    <div class="left">
                        <li>
                            <label class="reg" for="unit">Unit</label>
                            <select id="unit">
                                <option value="default" default>--No selection--</option>
                                <option value="db">ICT285-Databases</option>
                                <option value="cos">ICT287-Computer Security</option>  
                                <option value="ea">ICT301-Enterprise Architectures</option>
                                <option value="itp">ICT302-IT Professional Practice Project</option>
                                <option value="sa">ICT373-Software Architectures</option>        
                            </select>                   
                        </li>
                        <li>
                            <label class="reg" for="assign">Assignment</label>
                            <select id="assign">
                                <option value="default" default>--No selection--</option>
                                <option value="as1">Assignment 1</option>
                                <option value="as2">Assignment 2</option>
                                <option value="as2">Assignment 3</option>                
                            </select>
                        </li>
                        <li>
                            <label class="reg" for="semester">Semester</label>
                            <input type="text" id="semester"> 
                        </li>
                        <li>
                            <label class="reg" for="year">Year</label>
                            <input type="text" id="year"> 
                        </li>
                    </div>
                
                    <div class="mem">
                        <li>
                            <label class="reg" for="mem">Group Members</label>
                            <input type="text" id="mem" placeholder="Group leader..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <li>
                            <label class="reg" for="mem"></label>
                            <input type="text" id="mem" placeholder="Member..."> 
                        </li>
                        <div class="btn-save">
                            <input type="submit" name="save" id="save" value="Submit">
                        </div>

                        <div class="btn-delete">
                            <input type="button" name="delete" id="delete" onclick="location.href='#'" value="Delete Group">
                        </div>
                    </div> 
                </ul>

                
            </form>
        </div>
    </div>

</body>
</html>