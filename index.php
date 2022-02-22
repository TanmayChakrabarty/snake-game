<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Snake Game</title>
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="jquery-ui.min.js"></script>
    <link type="text/css" rel="stylesheet" href="styles.css" />
</head>

<body>
<div id="whole_container">
    <div id="Title_Bar">
    	Tanmay's Snake Game
    </div>
    <div style="overflow:hidden;margin-bottom:10px;">
        <div class="fl">
            <div id="container">
                <div class="the_modal_container"><div class="body"></div><div class="buttons_container"></div></div>
            </div>
        </div>
        <div class="fr" style="width:380px;">
        	<fieldset class="tlc">
            	<legend>Controls</legend>
                <button onClick="start_end_game();" id="start_button" disabled="disabled">Start Game</button>
                <button onClick="play_pause_game('manual');" id="pause_button" disabled="disabled">Pause Game</button>
            </fieldset>
            <fieldset>
                <legend>Score</legend>
                <div id="current_score" style="">
                    <div><span id="this_level">1</span></div>
                    <div><span id="lifes">3</span></div>
                    <!--Level's Score: <span id="current">0</span>-->
                    <div><span id="total_score">0</span></div>
                </div>
            </fieldset>
            <fieldset>
            	<legend>About the Game</legend>
                <b><u>Shortcuts:</u></b><br />
                Play & Pause => Space Bar (also Esc)
                <br />
                Start & End => S
                <br /><br />
                Click on "Star Game" button and use left, right, top, bottom arrow keys on your keyboard to move the snake correspondly. Whenever there is an apple, try to reach and eat the apple to get 1 point.
                <br />
                <br />
                <b><u>Hint:</u></b> Pressing an arrow key will force the snake to move faster that way.
            </fieldset>
        </div>
    </div>
    <fieldset id="addv">
    	<div class="fl">
        	Created By Tanmay Chakrabarty
        </div>
        <div class="fr">
        	<a href="http://tanmayonrun.blogspot.com">
            	More at Tanmay On Run Blog
            </a>
        </div>
    </fieldset>
</div>

<script type="text/javascript">
	
	
	var game_started = false;
	var game_paused = false;
	var force_evil_random_move = false;
	var modal_on = false;
	var modal_answer = -1;
	
	var total_pixels_x = (600 / 20);
	var total_pixels_y = (600 / 20);
	var total_pixels = total_pixels_x * total_pixels_y;
	var snake_body = new Array();
	var evil_snake_body = new Array();
	var evil_snake_on_the_ground = false;
	var current_direction = "R";
	var evil_snake_direction = "R";
	var lifes = 3;
	
	var snake_run_interval = 0;
	var snake_food_interval = 0;
	var evil_snake_run_interval = 0;
	
	var evil_snake_speed = new Array();
	evil_snake_speed[1] = 250;
	evil_snake_speed[2] = 200;
	evil_snake_speed[3] = 150;
	evil_snake_speed[4] = 100;
	evil_snake_speed[5] = 50;
	
	var snake_speed = new Array();
	snake_speed[1] = 500;
	snake_speed[2] = 400;
	snake_speed[3] = 300;
	snake_speed[4] = 200;
	snake_speed[5] = 100;
	
	var food_speed = new Array();
	food_speed[1] = 5000;
	food_speed[2] = 4500;
	food_speed[3] = 4000;
	food_speed[4] = 3500;
	food_speed[5] = 3000;
	
	var head_direction = "right";
	var evil_snake_head_direction = "right";
	
	var count_eaten_food = 0;
	var count_eaten_food_total = 0;
	var evil_snake_eaten_food = 0;
	
	var the_stage = 1;
	var total_stage = 5;
	
	var stage_food_limit = new Array();
	stage_food_limit[1] = 20;
	stage_food_limit[2] = 15;
	stage_food_limit[3] = 12;
	stage_food_limit[4] = 10;
	stage_food_limit[5] = 10;
		
	var max_food_supply = new Array();
	max_food_supply[1] = 40;
	max_food_supply[2] = 30;
	max_food_supply[3] = 25;
	max_food_supply[4] = 20;
	max_food_supply[5] = 15;
	
	var supplied_food = 0;

	produce_grid(total_pixels,function(){
		$("#start_button").removeAttr("disabled");
		$("#pause_button").removeAttr("disabled");
		});
	
	/*Initialization Part*/
	function produce_grid(total_pixels, callback){
		for(i=1;i<=total_pixels_y;i++){
			for(j=1;j<=total_pixels_x;j++){
				$("#container").append("<div id='" + i + "_" + j + "' class='pixels'></div>");
				}
			}
		callback();
		}
	function set_stage(stage_number,callback,call_type){
		//clearing board
		$(".pixels").removeClass("holding_snake the_head head_image_B head_image_L head_image_R head_image_T holding_evil_snake the_evil_head evil_head_image_B evil_head_image_L evil_head_image_R evil_head_image_T food obstacle");
		if(stage_number == 1){
			if(game_started && call_type == "entry"){
				var data = "<div style=\"text-align:center;\"><img src=\"images/your_snake.png\" /></div><br /><br />";
				data += "<h3>This is your snake. Try to eat foods and just don't bite yourself.<br /><br />REMEMBER: In every level, you will have to eat a certain number of foods and luckily you will be provided with more foods than target number of foods.</h3>";
				show_modal(data,1);
				}
			}
		else if(stage_number == 2){

			}
		else if(stage_number == 3){
			if(game_started && call_type == "entry"){
				var data = "<div style=\"text-align:center;\"><img src=\"images/the_evil_snake.png\" /></div><br /><br />";
				data += "<h3>Here comes the evil snake.<br /><br />You have to be a little more faster when forwarding to foods as the evil snake is 2x faster than you and will get to food faster (in most of the cases).<br /><br />NOTICE: Evil snake eat your foods. It may that, it will eat up so many foods which won't leave enough foods for you to eat and pass the level. Thus, compete with the evil snake in eating foods.</h3>";
				show_modal(data,1);
				initialize_evil_snake();
				}
			}
		else if(stage_number == 4){
			if(game_started && call_type == "entry"){
				var data = "<h3>Here comes the obstacles, another pain for you.<br /><br />NOTICE: Obstacles are those black blocks on the ground and those are deadly, don't try to pass them, it will kill you. However, you can move by touching the obstacles, but you can't pass them over.</h3>";
				show_modal(data,1);
				initialize_evil_snake();
				}
			//top-bottom, left side
			for(var i=1;i<=(total_pixels_y/3);i++){
				$("#1_"+i).addClass("obstacle");
				$("#"+total_pixels_x+"_"+i).addClass("obstacle");
				}
			//top-bottom, right side
			for(var i=(parseInt(total_pixels_y/3) * 2);i<=total_pixels_y;i++){
				$("#1_"+i).addClass("obstacle");
				$("#"+total_pixels_x+"_"+i).addClass("obstacle");
				}
			//left-right, up side
			for(var i=1;i<=(total_pixels_x/3);i++){
				$("#"+i+"_1").addClass("obstacle");
				$("#"+i+"_"+total_pixels_y).addClass("obstacle");
				}
			//left-right, down side
			for(var i=(parseInt(total_pixels_x/3) * 2);i<=total_pixels_x;i++){
				$("#"+i+"_1").addClass("obstacle");
				$("#"+i+"_"+total_pixels_y).addClass("obstacle");
				}
			}
		else if(stage_number == 5){
			if(game_started && call_type == "entry"){
				initialize_evil_snake();
				}
			//top-bottom
			for(var i=1;i<=total_pixels_y;i++){
				$("#1_"+i).addClass("obstacle");
				$("#"+total_pixels_x+"_"+i).addClass("obstacle");
				}
			//left-right, up side
			for(var i=1;i<=total_pixels_x;i++){
				$("#"+i+"_1").addClass("obstacle");
				$("#"+i+"_"+total_pixels_y).addClass("obstacle");
				}
			}
		count_eaten_food = 0;
		supplied_food = 0;
		callback();
		}

	function initialize_snake(){
		kill_the_snake();
		
		current_direction = "R";
		var the_x = parseInt(total_pixels_x / 2);
		var the_y = parseInt(total_pixels_y / 2) - 5;
		snake_body = [];		
		snake_body.push(the_x + "_" + the_y);
		the_y++;
		snake_body.push(the_x + "_" + the_y);
		the_y++;
		snake_body.push(the_x + "_" + the_y);
		the_y++;
		snake_body.push(the_x + "_" + the_y);
		the_y++;
		snake_body.push(the_x + "_" + the_y);
		the_y++;

		create_snake();
		autorun_snake();
		autorun_food();
		}
	function create_snake(){
		$(snake_body).each(function(index, element) {
			var this_id = "#" + element;
			$(this_id).addClass("holding_snake");
			});
		element = snake_body[snake_body.length - 1];
		this_id = "#" + element;
		$(this_id).addClass("the_head head_image_" + current_direction);
		}
	/*-------------------*/
	
	/*Game Start*/
	function show_modal(message,button){
		$(".the_modal_container .body").html(message);
		if(button == 1)
			$(".the_modal_container .buttons_container").html("<button onClick=\"hide_modal(0);\">Ok, Close</button>");
		else if(button == 2){
			$(".the_modal_container .buttons_container").html("<button onClick=\"hide_modal(1);\">Yes</button><button onClick=\"hide_modal(0);\">No</button>");
			}
		else{
			$(".the_modal_container .buttons_container").html("");
			}
		modal_on = true;
		
		if(!game_paused) play_pause_game('auto');
		
		$(".the_modal_container").show();
		$(".the_modal_container .buttons_container button").eq(0).focus();
		}
	function hide_modal(action){
		if(modal_on){
			modal_answer = action;
			modal_on = false;
			$(".the_modal_container").hide();
			play_pause_game('auto');
			}
		}
	function start_end_game(){
		if(!game_started){
			game_started = true;
			$("#start_button").text("End Game");
			set_stage(the_stage,function(){
				initialize_snake();
				},"entry");
			}
		else{
			end_the_game("manual");
			}
		}
	function play_pause_game(call_type){
		if(game_started){
			if(game_paused){
				if(call_type == 'manual'){
					hide_modal(0);
					}
				if(!modal_on){
					game_paused = false
					$("#pause_button").text("Pause Game");
					}
				}
			else{
				game_paused = true;
				if(call_type == 'manual'){
					var data = "<h1>The Game is Paused.</h1><br /><br /><h3>Press \"P\" to play or click the following button.</h3><br /><br /><button onClick = \"play_pause_game('manual');\">Play</button>";
					show_modal(data,-1);
					}
				$("#pause_button").text("Play Game");
				}
			}
		}
	function autorun_snake(){
		snake_run_interval = setInterval(function(){
			if(!game_paused){
				move_snake(current_direction);
				}
			},snake_speed[the_stage]);
		}
	function autorun_food(){
		snake_food_interval = setInterval(function(){
			if(!game_paused){
				var found_position = false;
				while(!found_position){
					var i = getRandomInt (1, total_pixels_x);
					var j = getRandomInt (1, total_pixels_y);
					$(".food").removeClass("food");
					if(	!$("#" + i + "_" + j).hasClass("holding_snake")
						&& !$("#" + i + "_" + j).hasClass("holding_evil_snake")
						&& !$("#" + i + "_" + j).hasClass("obstacle")
						){
						$("#" + i + "_" + j).addClass('food');
						
						if(supplied_food > max_food_supply[the_stage]){
							food_finished("Maximum number of foods for this level were supplied and you failed to eat the required number of them. Sorry, Try Again.");
							}
						else if(((max_food_supply[the_stage] - supplied_food) + count_eaten_food) < stage_food_limit[the_stage]){
							food_finished("Ooops !!! You can't pass this level anymore, even if you eat all the remaining foods. Sorry, Try Again")
							}
						supplied_food++;
						found_position = true;
						}
					}
				
				}
			},food_speed[the_stage]);

		}	
	/*----------*/
	
	/*In Game Functions*/
	function move_snake(direction){
		if(game_paused) return;
		if(if_possible_direction(direction,current_direction) == 0) return;
		
		var tail_position = snake_body[0];
		tail_position = tail_position.split("_");
		var tail_i = parseInt(tail_position[0]);
		var tail_j = parseInt(tail_position[1]);
		
		var head_index = snake_body.length - 1;
		var head_position = snake_body[head_index];
		head_position = head_position.split("_");
		var head_i = parseInt(head_position[0]);
		var head_j = parseInt(head_position[1]);
		
		var new_i = 0;
		var new_j = 0;
		
		var new_position = get_new_positions(direction, head_i, head_j);
		new_i = new_position[0];
		new_j = new_position[1];
		
		var possible_position = if_possible_position(new_i,new_j);
		if(possible_position == 1){
			current_direction = direction;
			paint_snake(tail_i,tail_j,new_i,new_j);
			}
		else if(possible_position == 0){
			snake_died("The snake died. Keep the snake away from obstacles and his own body next time. You score is : " + count_eaten_food_total);
			}
		}
	function paint_snake(tail_i,tail_j,new_i,new_j){
		var food_eaten = false;
		if(!$("#" + new_i + "_" + new_j).hasClass("food")){
			var action_id = "#" + tail_i + "_" + tail_j;
			$(action_id).removeClass("holding_snake");
			}
		else{
			count_eaten_food++;
			count_eaten_food_total++;
			$("#current_score #current").text(count_eaten_food);
			$("#current_score #total_score").text(count_eaten_food_total);
			food_eaten = true;
			}
		var action_id = "#" + new_i + "_" + new_j;
		$(action_id).addClass("holding_snake");
		
		if(food_eaten) $("#" + new_i + "_" + new_j).removeClass("food");
		else snake_body.splice(0,1);
		
		$(".pixels").removeClass("the_head head_image_B head_image_L head_image_R head_image_T");
		$(action_id).addClass("the_head head_image_" + current_direction);
		action_id = new_i + "_" + new_j;
		snake_body.push(action_id);
		
		if(food_eaten) is_level_change();
		}
	function kill_the_snake(){
		try{
			clearInterval(snake_run_interval);
			clearInterval(snake_food_interval);
			}
		catch(e){
			alert("interval clear failed.");
			}
		}
	/*-----------------*/
	
	/*Actions*/
	$(document).on("keydown",function(e){
		//right arrow
		if(e.keyCode == 39){
			move_snake("R");
			e.preventDefault();
			}
		//left arrow
		else if(e.keyCode == 37){
			move_snake("L");
			e.preventDefault();
			}
		//UP arrow
		else if(e.keyCode == 38){
			move_snake("T");
			e.preventDefault();
			}
		//Down arrow
		else if(e.keyCode == 40){
			move_snake("B");
			e.preventDefault();
			}
		//Play_Pause
		else if(e.keyCode == 32 || e.keyCode == 27){
			play_pause_game('manual');
			}
		//Start_End Game
		else if(e.keyCode == 83){
			start_end_game();
			}
		/*else
			alert(e.keyCode);*/
		});
	/*-------*/
	
	/*Common*/
	function getRandomInt (mins, maxs) {
				return Math.floor(Math.random() * (maxs - mins + 1)) + mins;
				}
	function get_new_positions(direction, head_i, head_j){
		var new_position = new Array();
			
		if(direction == "B"){
			new_position[0] = head_i + 1;
			new_position[1] = head_j;
			}
		else if(direction == "T"){
			new_position[0] = head_i - 1;
			new_position[1] = head_j;
			}
		else if(direction == "R"){
			new_position[0] = head_i;
			new_position[1] = head_j + 1;
			}
		else if(direction == "L"){
			new_position[0] = head_i;
			new_position[1] = head_j - 1;
			}
			
		if(new_position[0] > total_pixels_x) new_position[0] = 1;
		if(new_position[0] < 1) new_position[0] = total_pixels_x;
		if(new_position[1] > total_pixels_y) new_position[1] = 1;
		if(new_position[1] < 1) new_position[1] = total_pixels_y;
		
		return new_position;
		}
	function if_possible_direction(target_dir,current_dir){
		if(target_dir == "R" && current_dir == "L") return 0;
		else if(target_dir == "L" && current_dir == "R") return 0;
		else if(target_dir == "B" && current_dir == "T") return 0;
		else if(target_dir == "T" && current_dir == "B") return 0;
		else return 1;
		}
	function if_possible_position(new_i,new_j){
		if($("#" + new_i + "_" + new_j).hasClass("holding_snake") || $("#" + new_i + "_" + new_j).hasClass("obstacle")) return 0;
		else if($("#" + new_i + "_" + new_j).hasClass("holding_evil_snake")) return 2;
		else return 1;
		}
	function is_level_change(){
		if(count_eaten_food == stage_food_limit[the_stage]){
			if(the_stage != total_stage){
				alert("Congratulations ! Level Complete. You score is : " + count_eaten_food_total);
				the_stage++;
				count_eaten_food = 0;
				evil_snake_eaten_food = 0;
				supplied_food = 0;
				evil_snake_on_the_ground = false;
				//updating score board
				$("#this_level").html(the_stage);
				$("#current").html(count_eaten_food);
				set_stage(the_stage,function(){
					initialize_snake();
					},"entry");
				}
			else{
				alert("Congratulations !!! Game Over !!! You score is : " + count_eaten_food_total);
				end_the_game("auto");
				}
			
			}
		}
	function food_finished(msg){
		lifes -= 1;
		$("#lifes").text(lifes);
		if(!lifes){
			alert(msg + "\nGame Over. You score : " + count_eaten_food_total);
			end_the_game("auto");
			}
		else{
			alert(msg + "\n\nThis level will start again.\nYour score of this level will be taken away.\n1 of your lives will be taken.\nRemaining Lives : "+ lifes);
			count_eaten_food_total -= count_eaten_food;
			$("#total_score").html(count_eaten_food_total);
			set_stage(the_stage,function(){
				initialize_snake();
				initialize_evil_snake();
				},"reset");
			}
		}
	function snake_died(msg){
		lifes -= 1;
		$("#lifes").text(lifes);
		if(!lifes){
			alert(msg);
			end_the_game("auto");
			}
		else{
			alert("The Snake Died.\nRemaining Lives : " + lifes);
			set_stage(the_stage,function(){
				initialize_snake();
				initialize_evil_snake();
				},"reset");
			}
		}
	function end_the_game(call_type){
		if(call_type == "manual"){
			if(!confirm("Do you really want to end this game?")) return;
			}
		lifes =3;
		the_stage = 1;
		count_eaten_food = 0;
		count_eaten_food_total = 0;
		evil_snake_eaten_food = 0;
		supplied_food = 0;
		$("#this_level").html(the_stage);
		$("#lifes").text(lifes);
		$("#current").html(count_eaten_food);
		$("#total_score").html(count_eaten_food_total);
		kill_the_snake();
		if(evil_snake_on_the_ground) kill_evil_snake();
		game_started = false;
		set_stage(the_stage,function(){
			$("#start_button").text("Start Game");
			},"entry");
		}					
	/*------*/

	/*Evil snake*/
	function initialize_evil_snake(){
		kill_evil_snake();
		
		evil_snake_direction = "R";

		var the_x = parseInt(total_pixels_x / 2);
		var the_y = parseInt(total_pixels_y / 2) + 1;
		evil_snake_body = [];	
			
		evil_snake_body.push(the_x + "_" + the_y);
		the_y++;
		evil_snake_body.push(the_x + "_" + the_y);
		the_y++;
		evil_snake_body.push(the_x + "_" + the_y);
		the_y++;
		evil_snake_body.push(the_x + "_" + the_y);
		the_y++;
		evil_snake_body.push(the_x + "_" + the_y);
		the_y++;

		create_evil_snake();
		autorun_evil_snake();
		}
	function create_evil_snake(){
		$(evil_snake_body).each(function(index, element) {
			var this_id = "#" + element;
			$(this_id).addClass("holding_evil_snake");
			});
		element = evil_snake_body[evil_snake_body.length - 1];
		this_id = "#" + element;
		$(this_id).addClass("the_evil_head evil_head_image_" + evil_snake_direction);
		}
	function autorun_evil_snake(){
		evil_snake_on_the_ground = true;
		evil_snake_run_interval = setInterval(function(){
			if(!game_paused){
				move_evil_snake();
				}
			},evil_snake_speed[the_stage]);
		
		}
	function move_evil_snake(){
		var direction = "";
		var target_point = "";
		var target_point_x = "";
		var target_point_y = "";
		var head_index = -1;
		var head_position = "";
		var head_x = -1;
		var head_y = -1;
		var path_found = false;
		var new_x = 0;
		var new_y = 0;
		var prime_dir = "";
		var second_dir ="";
		
		if($(".food").length > 0 && !force_evil_random_move){
			target_point = $(".food").attr("id");
			target_point = target_point.split("_");
			target_point_x = parseInt(target_point[0]);
			target_point_y = parseInt(target_point[1]);
			
			head_index = evil_snake_body.length - 1;
			head_position = evil_snake_body[head_index];
			head_position = head_position.split("_");
			head_x = parseInt(head_position[0]);
			head_y = parseInt(head_position[1]);
			
			if(!path_found && target_point_x > head_x){
				if((target_point_x - head_x) <= (head_x + (total_pixels_x - target_point_x))){
					prime_dir = "B";
					second_dir = "T";
					}
				else{
					prime_dir = "T";
					second_dir = "B";
					}
				if(if_possible_direction(prime_dir,evil_snake_direction) == 1){
					direction = prime_dir;
					path_found = true;
					}
				else if(if_possible_direction(second_dir,evil_snake_direction) == 1){
					direction = second_dir;
					path_found = true;
					}
				}
			if(!path_found && target_point_x < head_x){
				if((head_x - target_point_x) <= (target_point_x + (total_pixels_x - head_x))){
					prime_dir = "T";
					second_dir = "B";
					}
				else{
					prime_dir = "B";
					second_dir = "T";
					}
				if(if_possible_direction(prime_dir,evil_snake_direction) == 1){
					direction = prime_dir;
					path_found = true;
					}
				else if(if_possible_direction(second_dir,evil_snake_direction) == 1){
					direction = second_dir;
					path_found = true;
					}
				}
			if(!path_found && target_point_y > head_y){
				if((target_point_y - head_y) <= (head_y + (total_pixels_y - target_point_y))){
					prime_dir = "R";
					second_dir = "L";
					}
				else{
					prime_dir = "L";
					second_dir = "R";
					}
				if(if_possible_direction(prime_dir,evil_snake_direction) == 1){
					direction = prime_dir;
					path_found = true;
					}
				else if(if_possible_direction(second_dir,evil_snake_direction) == 1){
					direction = second_dir;
					path_found = true;
					}
				}
			if(!path_found && target_point_y < head_y){
				if((head_y - target_point_y) <= (target_point_y + (total_pixels_y - head_y))){
					prime_dir = "L";
					second_dir = "R";
					}
				else{
					prime_dir = "R";
					second_dir = "L";
					}
				if(if_possible_direction(prime_dir,evil_snake_direction) == 1){
					direction = prime_dir;
					path_found = true;
					}
				else if(if_possible_direction(second_dir,evil_snake_direction) == 1){
					direction = second_dir;
					path_found = true;
					}
				}
				
			if(!path_found) return;
			}
		else if(!force_evil_random_move){
			direction = evil_snake_direction;
			}
		else{
			while(true){
				var random_direction = getRandomInt(1,4);
				if(random_direction == 1) direction = "R";
				else if(random_direction == 2) direction = "B";
				else if(random_direction == 3) direction = "L";
				else if(random_direction == 4) direction = "T";
				if(if_possible_direction(direction,evil_snake_direction) == 1) break;
				}
			}
		
		var tail_position = evil_snake_body[0];
		tail_position = tail_position.split("_");
		var tail_x = parseInt(tail_position[0]);
		var tail_y = parseInt(tail_position[1]);
		
		if(head_x == -1 && head_y == -1){
			head_index = evil_snake_body.length - 1;
			head_position = evil_snake_body[head_index];
			head_position = head_position.split("_");
			head_x = parseInt(head_position[0]);
			head_y = parseInt(head_position[1]);
			}
		
		var new_position = get_new_positions(direction, head_x, head_y);
		new_x = new_position[0];
		new_y = new_position[1];
			
		var if_possible_move = if_evils_possible_position(new_x,new_y);
		
		if(if_possible_move == 1){
			force_evil_random_move = false;
			evil_snake_direction = direction;
			paint_evil_snake(tail_x,tail_y,new_x,new_y);
			}
		else force_evil_random_move = true;
		}
	function if_evils_possible_position(new_i,new_j){
		if($("#" + new_i + "_" + new_j).hasClass("holding_snake") || $("#" + new_i + "_" + new_j).hasClass("obstacle")/* || $("#" + new_i + "_" + new_j).hasClass("holding_evil_snake")*/) return 0;
		else return 1;
		}
	function paint_evil_snake(tail_i,tail_j,new_i,new_j){
		var food_eaten = false;
		if(!$("#" + new_i + "_" + new_j).hasClass("food")){
			var action_id = "#" + tail_i + "_" + tail_j;
			$(action_id).removeClass("holding_evil_snake");
			}
		else{
			evil_snake_eaten_food++;
			food_eaten = true;
			}
		var action_id = "#" + new_i + "_" + new_j;
		$(action_id).addClass("holding_evil_snake");
		
		if(food_eaten) $("#" + new_i + "_" + new_j).removeClass("food");
		else evil_snake_body.splice(0,1);
		
		$(".pixels").removeClass("the_evil_head evil_head_image_B evil_head_image_L evil_head_image_R evil_head_image_T");
		$(action_id).addClass("the_evil_head evil_head_image_" + evil_snake_direction);
		action_id = new_i + "_" + new_j;
		evil_snake_body.push(action_id);
		}
	function kill_evil_snake(){
		try{
			clearInterval(evil_snake_run_interval);
			evil_snake_on_the_ground = false;
			}
		catch(e){
			alert("evil interval clear failed.");
			}
		}
	/*----------*/
	
</script>
</body>
</html>
