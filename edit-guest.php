<?php 
  $PAGE_TITLE = "Edit Guest";
  require 'header.php'; 
?>

<form id="edit-person-form">
	<fieldset>
		<div class="row">
			<div class="col-md-12">
				<h2>Edit Attendee's</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<p>Manage and edit attendee information. This will update the information in the app for the attendees's bio page. It will also be reflected in the speaker's bio for any attendee who is giving a presentation.</p>
			</div>
			<div class="col-md-6">
				<label for="inputInterests">Select A Guest</label>
				<div class="input-append">
					<select class="form-control" id="select-person" style="margin-right:10px;">
						<option value="">Chose One!</option>
					</select>
					<br>
					<button class="btn btn-primary" type="button" id="editPerson" >Edit Guest</button>
					<button class="btn btn-danger pull-right" data-id=""id="deleteGuest" style="display:none; margin-left:10px;">Delete Guest</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
			</div>
		</div>
		<div class="row edit-form" style="display:none;">
			<div class="col-md-6">
				<h4>Personal Info</h4>
				<div class="form-group">
					<label class="control-label" for="inputName">Full Name</label>
					<input class="form-control validate" type="text" id="inputName" placeholder="Enter Your Name Here" value=""></div>
				<div class="form-group">
					<label for="inputEmail" value="">Email</label>
					<input class="form-control validate" type="text" id="inputEmail" placeholder="Enter Your Email Here"></div>
				<div class="form-group">
		          <label for="inputEmail" value="">Phone</label>
		          <input class="form-control validate" type="tel" id="inputPhone" placeholder="XXX-XXX-XXXX"></div>
				<div class="form-group">
					<label for="profilePhotoFileUpload">Avatar Image</label><br>
					<img src="" id="avatraImage" class="img-thumbnail clearfix" width="150" /><br> <br>
					<input type="file" id="profilePhotoFileUpload"></div>
				<div class="form-group ">
					<label for="expertiseField clearfix">Organizations</label>
					<select class="form-control" id="expertiseField">
						<option value="">None</option>
					</select>
				</div>
				<div class="form-group">
					<label for="inputBio">Bio</label>
					<textarea rows="13" id="inputBio" class="form-control"></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<h4>Social Profile</h4>
		        <div class="form-group">
		          <label for="inputFacebook">Facebook ID</label>
		          <input class="form-control" type="text" id="inputFacebook" placeholder="your.user.name">
		          <span class="help-block">Enter the last portion of your facebook profile URL, (http://facebook.com/<strong>your.user.name</strong>) </span>
		        </div>
				<div class="form-group">
		          <label for="inputTwitter">Twitter Handle</label>
		          <input class="form-control" type="text" id="inputTwitter" placeholder="your.twitter.handle">
		          <span class="help-block">Enter your twitter handle, (@<strong>your.twitter.handle</strong>) </span>
		        </div>
		        <div class="form-group">
		          <label for="inputLinkedin">Linkedin</label>
		          <input class="form-control" type="text" id="inputLinkedin" placeholder="xxxxxx">
		          <span class="help-block">Enter the 'id=' user number from your Linkedin profile URL, (http://linkedin.com/profile/view?id=<strong>xxxxxx</strong>) </span>
		        </div>
		        <div class="form-group">
		          <label for="inputGoggle">Google +</label>
		          <input class="form-control" type="text" id="inputGoogle" placeholder="xxxxxxxxxxxx"></div>
		          <span class="help-block">Enter the user number from your Google Plus profile URL, (http://plus.google.com/<strong>xxxxxxxxxxxx</strong>) </span>
		        <hr>
		        <div class="form-group">
		          <label for="inputInterests">Areas of Expertise</label>
		          <span class="help-block">Areas of Expertise will apear on the bio page, and will also appear as a filer on the <strong>Directory</strong> page.</span>
		          <div class="row">
		            <div class="col-xs-12 col-md-9">
		              <select class="form-control" id="interest">
		                <option value="">None</option>
		                <option value="Foodie">Real Estate</option>
		                <option value="Buying">Buying</option>
		                <option value="Selling">Selling</option>
		              </select>
		            </div>
		            <div class="col-xs-12 col-md-3">
		              <button class="btn btn-success" type="button" id="addInterest" >Add</button>
		              <button class="btn btn-danger" type="button" id="clearInterest" >Clear</button>
		            </div>
		          </div>
		          <ul class="multi-list" id="interests"></ul>
		        </div>
		        <div class="form-group">
		          <label for="inputPassword">Languages</label>
		          <span class="help-block">You can add up to three languages that will appear in the app. </span>
		          <div class="row">
		            <div class="col-md-9">
		              <input class="form-control" type="text" id="factField" placeholder="Enter Languages" >
		            </div>
		            <div class="col-md-3">
		              <button class="btn btn-success" type="button" id="addFact" >Add</button>
		              <button class="btn btn-danger" type="button" id="clearFact" >Clear</button>
		            </div>
		          </div>
		          <ul id="factList" class="multi-list"></ul>
		        </div>
		      </div>
			</div>
			<div class="row edit-form" style="display:none;">
				<div class="col-md-6">
					<div class="form-group">
						<hr>
						<input type="submit" class="btn btn-success btn-lg btn-block" value="Save Guest" >
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>
	</fieldset>
</form>

<?php require 'modal.php'; ?>

<script type="text/javascript">
  Parse.initialize("bzxMfkPDky6xy8G6rTjH39N2GG3U08G6NaSjPTLg", "WuQ4EQv7CW1IVX8wrdOnTOYeEUovU0vqWOGZxZfp");

  $(document).ready(function() {

  	//Pulls Persons Object
    var Person = Parse.Object.extend("Person");
    var personQuery = new Parse.Query(Person);
    personQuery.include('organization');
    //var people = new Person();


    //Pull Organizations Pbject
	var Organization = Parse.Object.extend("Organization");
	var orgs = new Parse.Query(Organization);
	var orgList = new Organization();

	//set arrays
    var personsObjectArray = [];
    var organizationObjectArray = [];
    var factArray = [];
    var interestArray = [];

    var person;

    //Grabs People and populates them in the personsObjectArray and populates the select box
    personQuery.limit(1000);
	personQuery.ascending("name").find({
		success: function(results) {
		  personsObjectArray = results;
		  for(var i = 0; i < results.length; i++) {
		    var 
		      entity = results[i],
		      name = entity.get('name'),
		      id = entity.id,
		      select = $('#select-person')
		    ;
		    //console.log(personsObjectArray);
		    select.append('<option value="' +  id + '">' + name + '</option>');
		  }
		},
		error: function(error) {
		  alert("Error: " + error.code + " " + error.message);
		}
	});

    //Grabs organizations and puts them in the organizationArray and populates the select box
    orgs.ascending("name").find({
        success: function(results) {
          organizationObjectArray = results;
          for(var i = 0; i < results.length; i++) {
            var 
              org = results[i],
              name = org.get('name'),
              id = org.get.id,
              select = $('#expertiseField')
            ;
             
            select.append('<option value="' + i + '">' + name + '</option>');
          }
          //console.log(organizationObjectArray);
        },
        error: function(error) {
          alert("Error: " + error.code + " " + error.message);
        }
      });

    //Grabs selected person from the list and populates the form
	$('#editPerson').on('click', editPersonInfo);
	var counter = 0;
	function editPersonInfo(){
		var
		  entityId =  $("#select-person option:selected").val(),
		  form = $('.edit-form'),
		  editBnt = $('#editPresentation'),
		  delButton = $('#deleteGuest')
		
		;
		counter++;
		form.show();
		delButton.show();
		editBnt.hide();
		delButton.attr('data-id', entityId);
		populateForm(entityId);
		if(counter>1){
			clearFacts();
			$('#avatraImage').attr('src', '');
		}

		console.log(counter);

		return true;
		

		//console.log(entityId);
	}

      //populate for function
    function populateForm(entityId){
		personQuery.get(entityId, {
			success: function(entity) {
				person = entity;
				var 
			        nameValue =  entity.get('name'),
			        emailValue =  entity.get('email'),
			        phoneValue =  entity.get('phoneMain'),
			        bioValue =  entity.get('bio'),
			        departmentValue =  entity.get('department'),
			        organizationValue =  entity.get('organization'),
			        facebookValue =  entity.get('facebookName'),
			        twitterValue =  entity.get('twitterName'),
			        linkedinValue =  entity.get('linkedinName'),
			        googleValue =  entity.get('googleName'),
			        factsValue =  entity.get('facts'),
			        fileUploadControl = $("#profilePhotoFileUpload"),
			        organizationValue = entity.get('organization'),
			        interestsValue =  entity.get('interests'),
			        avatarImage = entity.get('profileImage')
			        //avatarImage = new Parse.File(name, file)
			    ;

				$("#inputName").val(nameValue);
				$("#inputEmail").val(emailValue);
				$("#inputPhone").val(phoneValue);
				$("#inputBio").val(bioValue);
				$("#inputFacebook").val(facebookValue);
				$("#inputTwitter").val(twitterValue);
				$("#inputLinkedin").val(linkedinValue);
				$("#inputGoogle").val(googleValue);


	            //adds to facts array and ul
			    factArray = factsValue;
			    $.each(factArray, function(val, fact) {
	            	$('ul#factList').append('<li>' + fact + '</li>');
	            });
	            //adds and selevyed department
				if ( departmentValue ) {
					$("#department option").filter(function(){
			        	return $(this).val() == departmentValue;
			        }).prop('selected', true);
				}
			    //Grabs organizations and
			    if ( organizationValue ) {
			    	$("#expertiseField option").filter(function(){
			        	return $(this).text() == organizationValue.get('name');
			        }).prop('selected', true);
				}

				//checks if there is an image, and then assigns it
				if ( avatarImage ) {
					avatraUrl = avatarImage.url()
					$('#avatraImage').attr('src', avatraUrl);
				}
				//adds to Area od Expertise array and ul
			    interestArray = interestsValue;
			    $.each(interestArray, function(val, interest) {
	            	$('ul#interests').append('<li>' + interest + '</li>');
	            });
			},
			error: function(object, error) {

				$('#modal-fail').modal('show');
			}
		});

  	}
  	$('#deleteGuest').on('click', deleteGuests);
  	function deleteGuests(el){
  		var 
  			personDest = $('#select-person option:selected').val(),
  			delId = $(this).attr('data-id')
  		;
  		el.preventDefault();
  		personQuery.get(delId, {
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

  	//Add / Clear Facts
  	$('#addFact').on('click', addFacts);
    $('#clearFact').on('click', clearFacts);
    function addFacts(){
     	var
          factField = $('#factField'),
          fact = factField.val(),
          list = $('ul#factList')
        ;

        list.append('<li>' + fact + '</li>');
        factField.val('');
        factArray.push(fact);

        console.log(factArray);
     }
    function clearFacts(){
    	var
          factField = $('#factField'),
          fact = factField.val(),
          list = $('ul#factList')
        ;

        list.html('');
        factArray = [];

        console.log(factArray);
    }
    //update the person
  	$('#edit-person-form').submit(function(e){
  		e.preventDefault();
  		updatePerson();
  	});

  	//Add / Clear interest  
    $('#addInterest').on('click', addInterest);
    $('#clearInterest').on('click', clearInterest);
    function addInterest(){
      var
          interest =  $("#interest option:selected").val(),
          interestsLists = $('ul#interests')
        ;

        interestsLists.append('<li>' + interest + '</li>');
        interestArray.push(interest);

        console.log(interestArray);
    }
    function clearInterest(){
      var
          factField = $('#interest'),
          fact = factField.val(),
          list = $('ul#interests')
        ;

        list.html('');
        interestArray = [];

        console.log(interestArray);
    }

  	function updatePerson(entityId){
  	//set vars for fields
      var 
      	//person = people;
        name =  $("#inputName").val(),
        email =  $("#inputEmail").val(),
        phone =  $("#inputPhone").val(),
        bio =  $("#inputBio").val(),
        department =  $("#department option:selected").val(),
        organizationIndex =  $("#inputOrganization option:selected").val(),
        orgName =  $("#inputOrganization option:selected").text(),
        facebook =  $("#inputFacebook").val(),
        twitter =  $("#inputTwitter").val(),
        google =  $("#inputGoogle").val(),
        linkedin =  $("#inputLinkedin").val()
      ;
      
      //person.set('organization', organizationObjectArray[organizationIndex]);

      cleanName = name.replace('&', '').replace('*', '').replace("'", "");
      //avatar upload
      var fileUploadControl = $("#profilePhotoFileUpload")[0];
      if (fileUploadControl.files.length > 0) {
        var file = fileUploadControl.files[0];
        var photoName = cleanName + " Avatar";
       
        var avatar = new Parse.File(photoName, file);
      }
      console.log(cleanName);

      //create object field for person
      person.set("name", name);
      person.set("email", email);
      person.set("phoneMain", phone);
      person.set("bio", bio);
      person.set("facts", factArray);
      person.set("profileImage", avatar);
      person.set("department", department);
      person.set("facebookName", facebook);
      person.set("twitterName", twitter);
      person.set("googleName", google);
      person.set("linkedinName", linkedin);
      person.set("interests", interestArray);

      //set the organization
      var org = organizationObjectArray[organizationIndex];
      person.set('organization', org);
      //person.set('title', organization.get('name'));

      //avatar upload
      // var fileUploadControl = $("#profilePhotoFileUpload")[0];
      // if (fileUploadControl.files.length > 0) {
      //   var file = fileUploadControl.files[0];
      //   var photoName = name + " Avatar";
       
      //   var avatar = new Parse.File(photoName, file);
      // }

      //save person
      person.save(entityId, {
        success: function(person) {
          $('#modal-success').modal('show');
        },
        error: function(person, error) {
          console.log(person);
          console.log(error);
          $('#modal-fail').modal('show');
        }
      });
  	}
  });
</script>

<?php require 'footer.php'; ?>