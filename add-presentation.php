<?php 
  $PAGE_TITLE = "Presentations";
  require 'header.php'; 
?>

<form id="edit-presentation-form" class="my-form">
	<fieldset>
		<div class="row">
			<div class="col-md-12">
				<h2>Add / Edit Presentation</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<p>You can either create a new presentation, or selct a presentation to edit. As you set up your presentations with dates and times, this will be reflected in the schedule of the app. Please keep in mind <strong>Events</strong> also effect your schedule.</p>
					<button class="btn block btn-large btn-success" id="add-presentation" style="margin-top:10px;">+ Add New Presentation</button>
				</div>
			</div>
			<div class="col-md-6">
				<label for="inputInterests">Select A Presentation</label>
			    <div class="form-group">
			        <select class="form-control" id="select-presentation" style="margin-right:10px;">
			         	<option value="">Chose One!</option>
			        </select><br>
			        <button class="btn btn-primary" type="button" id="editPresentation" >Edit Presentation</button>

					<button class="btn block btn-large btn-danger pull-right" id="delete-presentation" data-id="" style="display:none;"> Delete Presentation</button>
			    </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 hidden-form" style="display:none;">
				<div class="form-group">
					<label class="control-label" for="elect-presenter">Presenter</label>
		      		 <select class="form-control" id="select-presenter" style="margin-right:10px;" class="form-control">
			         	<option value="">Chose One!</option>
			        </select>
			    </div>
			    <div class="form-group">
		      		<label class="control-label" for="inputPresentationName">Presentation Name</label>
		      		<input class="col-md-12 validate form-control" type="text" id="inputPresentationName" placeholder="Presentation Name">
	      		</div>
			    <div class="form-group">
		      		<label>Start Date / Time</label>
		      		<input type="text" id="inputDatepicker" class="datePickerField form-control" />
		      	</div>
			    <div class="form-group">
		      		<label>End Date / Time</label>
		      		<input type="text" id="inputDatepickerEnd" class="datePickerField form-control" />
		      	</div>
	      	</div>
	      	<div class="col-md-6 hidden-form form-group" style="display:none;">
				<label for="inputBio">Presentation Description</label>
	      		<textarea rows="10" id="inputPresentationDescription" class="form-control"></textarea>
	      		<span class="help-block">This text will appear on the app's schedule page and should contain the nessesary information about the presentation's content.</span>
			</div>
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-6">
				<hr>
				<button type="submit" class="btn btn-lg btn-success btn-block" style="margin-right:10px;">Save Presentation</button>
			</div>
			<div class="col-md-6">
			</div>
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-12">
				<br><br>
				<h3>Add slides to your presentations</h3>
			</div>
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-6">
				
				<p>Manage slides in your presentation. Each slide must be uploaded as an image file, preferably a <strong>.jpg</strong>. To reorder the slides, simply drag and drop them to their new position. It's that easy!</p>
			</div>
			<div class="col-md-6">
				<p class="help-block"><strong>Quick Tip:</strong> You can upload multiple slides at once by adding a cell for each slide, and selecting each slide's image before saving.</p>
			</div>
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-12">
				<hr>
				<button class="btn btn-primary addSlide">+ Add A Slide</button>
				<br><br>
			</div>
		</div>
		<div class="row hidden-form"  id="slideWrapper" style="display:none;">
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-12">
				<button class="btn btn-primary addSlide">+ Add A Slide</button>
			</div>
		</div>
		<div class="row hidden-form" style="display:none;">
			<div class="col-md-6">
				<hr>
				<button type="submit" class="btn btn-lg btn-success btn-block" style="margin-right:10px;">Save Presentation</button>
				<br><br>
			</div>
			<div class="col-md-6">
			</div>
		</div>
	</fieldset>
</form>

<?php require 'modal.php'; ?>

<script type="text/javascript">
  Parse.initialize("bzxMfkPDky6xy8G6rTjH39N2GG3U08G6NaSjPTLg", "WuQ4EQv7CW1IVX8wrdOnTOYeEUovU0vqWOGZxZfp");
$(document).ready(function() {
  	//Pulls Presentation Object from DB
    var Presentation = Parse.Object.extend("Presentation");
    var presentationQuery = new Parse.Query(Presentation);
    presentationQuery.include('presenter');
    
    //Pulls Persons Object from DB
    var Person = Parse.Object.extend("Person");
    var personQuery = new Parse.Query(Person);

    //Pulls Slides Object from DB
    var Slide = Parse.Object.extend("Slide");
    var slideQuery = new Parse.Query(Slide);
    var mySlide = new Slide();

    //Arrays
    var presenterObjectArray = [];
    var slidesObjectArray = [];

    //sets var for presentaion id when editing as opposed to creating a new pres
    var thisPres;
    //global var to keep track of the slide count
    var slideCount = 0;

    //Date and Time picker
    $('.datePickerField').datetimepicker({
  //   	dateFormat: 'yy-mm-ddT',
  //   	timeFormat: 'HH:mm.z',
  //   	timezoneList: [ 
		// 	{ value: -300, label: 'Eastern'}, 
		// 	{ value: -360, label: 'Central' }, 
		// 	{ value: -420, label: 'Mountain' }, 
		// 	{ value: -480, label: 'Pacific' } 
		// ]
    });
    //grabs presentations and populates the select
    presentationQuery.ascending("slides");
    presentationQuery.find({
		success: function(results) {
			slidesObjectArray = results;
		  	for(var i = 0; i < results.length; i++) {
			    var 
			      pres = results[i],
			      name = pres.get('name'),
			      id = pres.id,
			      select = $('#select-presentation'),
			      slides =  pres.relation('slides')
			    ;
			    select.append('<option value="' +  id + '">' + name + '</option>');
		  	}
		},
		error: function(error) {
		  alert("Error: " + error.code + " " + error.message);
		}
	});
	//Grabs People and populates them in the personsObjectArray and populates the select box
	personQuery.limit(1000);
	personQuery.ascending("name");
	personQuery.find({
		success: function(results) {
		  presenterObjectArray = results;
		  for(var i = 0; i < results.length; i++) {
		    var 
		      entity = results[i],
		      name = entity.get('name'),
		      id = entity.id,
		      select = $('#select-presenter')
		    ;
		    select.append('<option value="' +  i + '">' + name + '</option>');
		  }
		},
		error: function(error) {
		  alert("Error: " + error.code + " " + error.message);
		}
	});
	//Add presentation
	$('#add-presentation').on('click', addPresentation);
  	function addPresentation(el){
		el.preventDefault();
		if( $('.hidden-form').is(':visible') ) {
    		clearFrom();
		}
		else {
		    showFrom();
		}
	}
	//Delete Presentation
	$('#delete-presentation').on('click', deletePresentation);
  	function deletePresentation(el){
		var 
  			personDest = $('select-presentation option:selected').val(),
  			delId = $(this).attr('data-id')
  		;
  		el.preventDefault();
  		presentationQuery.get(delId, {
			success: function(delId) {
				$('#modal-success').modal('show');
				$('#modal-fail .modal-body').html('<p>You Successfully deleted the person you did not seem to like anyway. Good going!</p>');
				delId.destroy({});
			},
			error: function(object, error) {
				$('#modal-fail').modal('show');
			}
		});
	}
	//unhides form
	function showFrom(){
		$('.hidden-form').show();
	}
	//parses the DOM and sets the slide index value. This happens when slides are sorted or deleted
	function updateIndex(){
		var
			wrapper = $(this).attr('id'),
			element = $('.slide-wrapper')
		;
		$.each(element, function(i, val){
			var
				slide = $(this).attr('id'),
				indexValue = $(this).attr('data-index'),
				newIndex = $(this).index(),
				slide = slidesObjectArray[indexValue]
			; 

			slide.set("index", newIndex);

		});
	}
	/***************
    ****************
	Add and delete slides
	****************
	****************/

	$('.addSlide').on('click', function(e){
		e.preventDefault();
		addSlideToForm();
		console.log(slideCount);
	});
	//add slide to form
	function addSlideToForm(){
		var 
			wrapper = $('#slideWrapper')
		;
		//adds new slide to the page
		wrapper.append('<div class="col-xs-12 col-sm-6 col-md-4 slide-wrapper new-slide form-group clearfix" id="slideWrapper' + slideCount + '" data-index="' + slideCount + '"><label for="inputPresentationName">Slide # ' + slideCount + '</label><a class="close" href="#" class="deleteSlide">×</a><div class="form-group"><img src="" style="background:url(http://placehold.it/350&text=Slide+' + slideCount + ') no-repeat center center #ccc; background-size: 100% auto;" width="100%" class="slide-image" /></div><div class="form-group"><input class="slideUploader" type="file" /></div><div class="form-group"><textarea rows="4"class="inputNotes form-control" placeholder="Enter notes for slide ' + slideCount + '"></textarea></div><hr></div>');
		//adds to counter
		slideCount++;
	}

	//Function to delete single slide from a presetation
	function deleteSlide(entity){
		console.log('Your ID is ' + entity);
		if ( entity ){
	  		slideQuery.get(entity, {
				success: function(entity) {
					//$('#modal-success').modal('show');
					console.log('you deleted ' + entity)
					$('#modal-fail .modal-body').html('<p>You Successfully deleted the person you did not seem to like anyway. Good going!</p>');
					entity.destroy({});
					updateIndex();
				},
				error: function(object, error) {
					$('#modal-fail').modal('show');
				}
			});
		}
		else {
			console.log('No Slides');
		}
	}

	/***************
    ****************
	Populate form and form functions
	****************
	****************/

	//Grabs selected presentations and populates the form 
	$('#editPresentation').on('click', editPresentation);
	function editPresentation(){
		var
		  entityId =  $("#select-presentation option:selected").val(),
		  form = $('#eedit-presentation-form'),
		  addBtn = $('#add-presentation'),
		  deleteBtn = $('#delete-presentation'),
		  editBnt = $('#editPresentation'),
		  wrapper = $('#slideWrapper')
		;
		//counter++;
		deleteBtn.attr('data-id', entityId);
		addBtn.hide();
		//editBnt.hide();
		wrapper.html('');
		deleteBtn.show();
		populatePresentationForm(entityId);
		showFrom();

		return true;
	}

	//function to populat form with presentation to be edited
	function populatePresentationForm(entityId){
		presentationQuery.get(entityId, {
			success: function(entity) {
				var 
			        presenterValue =  entity.get('presenter'),
			        nameValue =  entity.get('name'),
			        infoValue =  entity.get('info'),
			        presenterValue =  entity.get('presenter'),
			        slidesValue =  entity.relation('slides'),
			        dateValue =  entity.get('date'),
			        endDateValue =  entity.get('endDate')

			    ;
			    //set global var so submit function knows to edit presentation, and not create a new one
			    thisPres = entity;
			    //slide query
			    
			    $("#inputPresentationName").val(nameValue);
				$("#inputPresentationDescription").text(infoValue);
				$('#inputDatepicker').val(dateValue);
				$('#inputDatepickerEnd').val(endDateValue);

				//Grabs organizations and
			    if ( presenterValue ) {
			    	$("#select-presenter option").filter(function(){
			        	return $(this).text() == presenterValue.get('name');
			        }).prop('selected', true);
				}
				//call to populate slides
				populateSlides(slidesValue);
			},
			error: function(object, error) {
				$('#modal-fail').modal('show');
			}
		});
	}
	function populateSlides(slidesValue){
		slidesValue.query().limit(500).ascending("index").find({
			success: function(results) {
			   	for(var i = 0; i < results.length; i++) {
			    	var 
				      slide = results[i],
				      id = slide.id,
				      index = slide.get('index'),
				      wrapper = $('#slideWrapper'),
				      image = slide.get('content'),
				      notes = slide.get('notes')
			    	;
			    	//checks if images exsits, if not loads a dummy image
			    	if ( image ){
			    		var imageUrl = image.url();
			    		mySlide.set('content', imageUrl);
			    	} else {
			    		var imageUrl = 'http://placehold.it/350x150&text=No+Slide+Added';
			    	}
			    	//sets notes
			    	mySlide.set('notes', notes);
			    	//creates slide on the page, all dumb JS style
			    	wrapper.append('<div class="col-xs-12 col-sm-6 col-md-4 slide-wrapper form-group clearfix" id="slideWrapper' + index + '" data-id="' + id + '" data-index="' + index + '"><label for="inputPresentationName">Slide # ' + index + '</label><a class="close delete-slide" href="#" data-id="' + id + '">×</a><div class="form-group"><a target="_blank" href="' +  imageUrl + '"><img data-url="' +  imageUrl + '" style="background:url(' +  imageUrl + ') no-repeat center center #ccc; background-size: 100% auto;" width="100%" class="slide-image" /></a></div><div class="form-group"><input class="slideUploader" type="file" ></div><div class="form-group"><textarea rows="4" class="inputNotes form-control" placeholder="Enter notes for slide ' + index + '">' +  notes + '</textarea></div><hr></div>');
					
	    			slideCount = i+1;
				}
				console.log(slideCount);
				//adds slides to the slide object
		    	slidesObjectArray = results;
		    	updateIndex();
		    	//call for deletion of slides
				$('.delete-slide').on('click', function(el){
					el.preventDefault();
					var 
						entity = $(this).attr('data-id'),
						slideToDelete = $(this).parent().parent()
					;
					slideToDelete.remove();
					deleteSlide(entity);
					
				});
			},
			error: function(){
				console.log('nope');
			}
		});
		//sortable call for slides
		$( "#slideWrapper" ).sortable({
			update: function(event, el){
				//runs the function to update the current slides index
				updateIndex();
			}
		});
		$( "#slideWrapper" ).disableSelection();
	}

	/***************
    ****************
	Submit Form to save Slides and Presentation
	****************
    ***************/

	//form submit
	$('#edit-presentation-form').submit(function(e){
  		e.preventDefault();
  		//check to see if there is a presentation to edit. If not it creates a new presentation.
  		if(thisPres) {
        	var presentation = thisPres;
        }
        else {
        	var presentation = new Presentation();
        }
        //checks if there are slides to save or not, and then chooses which function to run
  		if (slideCount > 0){
  			updateSlides(presentation);
  		} else {
  			updatePresentation(presentation);
  		}
  	});

	//function to edit or save presentation
  	function updateSlides(presentation){
  		//pop open loading screen
  		$('#modal-loading').modal('show');

  		var 
        	relation = presentation.relation('slides'),
        	slidesSend = $('#slideWrapper .slide-wrapper'),
        	elems = $(slidesSend).nextAll(), 
        	count = elems.length+1 //number of slides to save
      	; 
      	//each loop over the slides
      	$.each(slidesSend, function(i, val){
      		var Slide = Parse.Object.extend("Slide");
      		//setting slide vars
      		var 
      			presentationName = presentation.get("name");
      			loopIndex = i,
      			slide = $(this).attr('id'),
      			dataId = $('#' + slide).attr('data-id'),
      			slideIndex = parseInt($('#' + slide).attr('data-index')),
      			slideImageDisplay = $('#' + slide +  ' img').data('data-url'),
      			slideNotes = $('#' + slide +  ' .inputNotes').val(),
      			slideUploader = $('#' + slide  +  ' .slideUploader')[0]
      		;
			//condition that either creats a new slide or updates a slide
			if($(this).hasClass('new-slide')){
      			var thisSlide = new Slide();

      			console.log('New slide');
      		}else{
      			var thisSlide = slidesObjectArray[slideIndex];
      			var slideIndex = thisSlide.get("index");
      		}
	      	
      		//console.log(slideIndex);
      		//grabs file from form and sets if
	      	if (slideUploader.files.length > 0) {
		        
		        //cleaning up any weird chatacters coming through on name for the DB
	      		//var cleanName = presentationName.replace('&', '').replace('*', '');
		        var file = slideUploader.files[0];
		        var photoName = slideIndex + " Slide Image";

		       
		        var slidePic = new Parse.File(photoName, file);
		        thisSlide.set('content', slidePic);
		    }
		    //sets note and index value
		    thisSlide.set('notes', slideNotes);
		    //checks for slide index and if spo sets it
		    thisSlide.set('index', slideIndex);
		    thisSlide.set('presentation', thisPres);

		    console.log(slideIndex + ' is the new index for slide ' + dataId);

		    //saves slides
		    thisSlide.save(null, {
		    	success: function(entity) {
		    		relation.add(thisSlide);
		    		console.log(slideIndex + ' slide was saved');
		    		//check for last slide to be saved, then runs the save presentation function
		    		if (!--count) {
				    	console.log('Last Count');
				    	updatePresentation(presentation)
				    } 
		    	},
		    	error: function(object, error) {
		    		$('#modal-loading').modal('hide');
		    		$('#modal-fail').modal('show');
		    		console.log(error);
		    	}

		    }); 
      	});
  	}

  	function updatePresentation(presentation){
        //setting form vars
  		var 
        	presenterIndex =  $("#select-presenter option:selected").val(),
        	name =  $("#inputPresentationName").val(),
        	startDateField =  $("#inputDatepicker").val().trim(),
        	endDateField =  $("#inputDatepickerEnd").val().trim(),
        	startDate = new Date(startDateField),
        	endDate = new Date(endDateField),
        	description =  $("#inputPresentationDescription").val(),
        	presenter = presenterObjectArray[presenterIndex]
      	;

  		presenter.set("isSpeaker", true);
      	//assiging values
  		presentation.set("presenter", presenter);
      	presentation.set("name", name);
      	presentation.set("date", startDate);
      	presentation.set("endDate", endDate);
      	presentation.set("info", description);
      	console.log('Saving Presentation ' + presentation);
      	//checking if a few things have been filled out

      	presentation.save(null, {
	        success: function(entity) {
	        	$('#modal-loading').modal('hide');
	          	$('#modal-success').modal('show');
	        },
	        error: function(object, error) {
	          console.log(presenterIndex);
	          console.log(error);
	          $('#modal-loading').modal('hide');
	          $('#modal-fail').modal('show');
	          $('#modal-fail .modal-body').append('<p>And error occured, dumbass. See below.</p><p>' + error + '</p>');
	        }
		});
  	}

});
</script>
<?php require 'footer.php'; ?>
