<?php 
  require 'header.php'; 
  $PAGE_TITLE = "Take Aways";
?>

<form id="edit-task-form" class="my-form">
	<fieldset>
		<div class="row">
			<div class="span12">
				<legend>Manage Take Aways and Tasks</legend>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<label for="inputInterests">Select A Task</label>
			    <div class="input-append">
			        <select class="input-xlarge" id="select-task" style="margin-right:10px;">
			         	<option value="">Chose One!</option>
			        </select>
			        <button class="btn" type="button" id="editTask" >Edit</button>
			    </div>
			</div>
			<div class="span6">
				<button class="btn block btn-medium btn-danger" id="delete-task" data-id="" style="margin-top:10px;display:none;"> Delete Task</button>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<label>Title</label>
				<input type="text" id="taskTitle" class="input span12">
				<label>Presentation</label>
				<select class="input span12" id="selectPresentation">
		         	<option value="">*</option>
		        </select>
		        <label>Category</label>
				<select class="input span12" id="selectCategory">
		         	<option value="">*</option>
		         	<option value="day1">Day 1</option>
		         	<option value="day2">Day2</option>
		         	<option value="other">Other</option>
		        </select>
			</div>
			<div class="span6">
				<label for="inputBio">Task Notes</label>
	      		<textarea rows="6" style="width:97%;" id="inputTaskNote"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<input type="submit" value="Save Task" class="btn btn-medium btn-success" id="add-presentation" style="margin-top:10px;">
			</div>
			<div class="span6">
				<!--  -->
			</div>
		</div>
	</fieldset>
</form>

<div class="row">
	<div class="span12">
		<h3>Take Aways</h3>
		<table class="table" id="taskTable">
	        <thead>
	          <tr>
	            <th>Title</th>
	            <th>Presentation</th>
	            <th>Notes</th>
	            <th>Edit</th>
	          </tr>
	        </thead>
	        <tbody>
	        </tbody>
	      </table>
	</div>
</div>

<?php require 'modal.php'; ?>

<script type="text/javascript">
  	Parse.initialize("jAxfiRG2eVYB2dcLju1qphCLCppqRgt3khUdb5AU", "vTyfhvPE6cyMzPr2oGBb8FYFdUhhil9UqTSrkGF0");
	$(document).ready(function() {
	
		//Pulls Persons Object from DB
	    var Task = Parse.Object.extend("Task");
	    var taskQuery = new Parse.Query(Task);

	     //Pulls Presentation Object from DB
	    var Presentation = Parse.Object.extend("Presentation");
	    var presentationQuery = new Parse.Query(Presentation);

	    //arrays
	    var tasksArray = [];
	    var presentationsObjectArray = [];

	    var thisTask;

	    //grabs tasks and populates the select
	    taskQuery.ascending().find({
			success: function(results) {
				tasksArray = results;
			  	for(var i = 0; i < results.length; i++) {
				    var 
				      	task = results[i],
						id = task.id,
						title = task.get('title'),
						note = task.get('note'),
						presentation = task.get('presentation'),
						staticTask = task.get('isStatic'),
						table = $('#taskTable tbody'),
						deleteBtn = $('.btn-delete'),
						select = $('#select-task')
				    ;
				    //loads presentation name if there is a presentation associated with it
				    if ( presentation ){
				    	var presentationName = presentation.get("name");
				    } else {
				    	var presentationName = '';
				    }
				    //check if it is a static task, if so add it to the options field and table
				    if ( staticTask == true ) {
				    	select.append('<option value="' +  id + '">' + title + '</option>');

				    	table.append('<tr><td>' + title + '</td><td>' + presentationName + '</td><td>' + note + '</td><td><button class="btn btn-small btn-danger btn-delete" id="' +  id + '">remove</button></td><td>');
				    }
			  	}
			  	deleteBtn.on('click', function(){
			    	var
			    		taskId = $(this).attr('id')
			    	;
			    	deleteTask(taskId);
			    });
			},
			error: function(error) {
			  alert("Error: " + error.code + " " + error.message);
			}
		});

		function deleteTask(taskId){
	  		taskQuery.get(taskId, {
				success: function(taskId) {
					taskId.destroy({});
					$('#modal-success').modal('show');
					$('#modal-success .modal-body').html('<p>You Successfully deleted the person you did not seem to like anyway. Good going!</p>');
				},
				error: function(object, error) {
					$('#modal-fail').modal('show');
				}
			});
	    }

		//grabs presentations and populates the select
	    presentationQuery.find({
			success: function(results) {
				presentationsObjectArray = results;
			  	for(var i = 0; i < results.length; i++) {
				    var 
				      pres = results[i],
				      name = pres.get('name'),
				      id = pres.id,
				      select = $('#selectPresentation'),
				      slides =  pres.relation('slides')
				    ;
				    select.append('<option value="' +  id + '">' + name + '</option>');
			  	}
			},
			error: function(error) {
			  alert("Error: " + error.code + " " + error.message);
			}
		});

		 /***************
	    ****************
		Populate for to edit task
		****************
	    ***************/

	    $('#editTask').on('click', editTask);
	    function editTask(){
	    	var
			  entityId =  $("#select-task option:selected").val()
			;
			populateForm(entityId);
	    }
	    function populateForm(entityId){
			taskQuery.get(entityId, {
				success: function(entity) {
					var 
						titleInput = entity.get("title"),
			      		categoryInput = entity.get("category"),
			      		summaryInput = entity.get("note"),
			      		presentation = entity.get("presentation"),
			      		presentationOptions = $("#selectPresentation option"),
			      		categoryOptions = $("#selectCategory option")
		      		;
		      		//checkes id presentation exists before setting the id
		      		if ( presentation ){
		      			var presentationId = presentation.id;
		      		}
		      		//sets var for resource to edit
					thisTask = entity;

		      		$("#taskTitle").val(titleInput); 
		        	$("#inputTaskNote").val(summaryInput);

		        	presentationOptions.filter(function(){
		        		return $(this).val() == presentationId;
		        	}).prop('selected', true);
		        	selectCategory

		        	categoryOptions.filter(function(){
		        		return $(this).val() == categoryInput;
		        	}).prop('selected', true);

		      	},
				error: function(object, error) {

					$('#modal-fail').modal('show');
				}
			});
		}






 		/***************
	    ****************
		Submit Form to save task
		****************
	    ***************/

	    $('#edit-task-form').submit(function(e){
	  		e.preventDefault();
	  		updateTask();
	  	});

	  	function updateTask(){
	  		//check to see if there is a resource to edit. If not it creates a new resource.
	  		if ( thisTask ){
	        	var task = thisTask;
	        }
	        else
	        {
	        	var task = new Task();
	        }
	  		var 
	  			newPresentation = new Presentation(),
	        	title =  $("#taskTitle").val(),
	        	presentationId =  $("#selectPresentation option:selected").val(),
	        	categoryVal =  $("#selectCategory option:selected").val(),
	        	note =  $("#inputTaskNote").val()
	      	;

	      	//sets id of selected presentation
		    newPresentation.id = presentationId;

	      	if ( presentationId ){
      			task.set("presentation", newPresentation);
      		}

		    //sets values for resource
	      	task.set("isStatic", true);
      		task.set("title", title);
      		task.set("category", categoryVal);
      		task.set("note", note);

      		if ( presentationId ){
      			task.set("presentation", newPresentation);
      		}

      		if (title==""){
		        $('#modal-fail').modal('show');
		        $('#modal-fail .modal-body').html('<p>Nope, You need to at least fill in the <strong>Title</strong>.</p><p> One more time, Slick.</p>');
		     } else{
			    task.save(null, {
			        success: function(entityId) {
			          	$('#modal-success').modal('show');
			        },
			        error: function(object, error) {
			          //console.log(presenterIndex);
			          console.log(error);
			          $('#modal-fail').modal('show');
			          $('#modal-fail .modal-body').append('<p>And error occured, dumbass. See below.</p><p>' + error + '</p>');
			        }
				});
	      	}
	  	}

	});
</script>

<?php require 'footer.php'; ?>