<?php 
  $PAGE_TITLE = "Add Guests";
  require 'header.php'; 
?>

<form id="person-form">
  <fieldset>
    <div class="row">
      <div class="col-md-12">
        <h2>Add Conference Attendee</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <p>Add in any attendee of your conference. This includes company staff, conference speakers and guests.</p>
      </div>
    <div class="col-md-6">
      <p class="help-block"><strong>Quick Tip:</strong> Attendees are organized by organizations in the app. When you add an attendee to an organizations, they will appear in the app's directory under that catagory.</p>
    </div>
    </div>
    <div class="row">
      <div class="col-md-12"><hr></div>
    </div>
    <div class="row">
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
          <label for="profilePhotoFileUpload">Avatar Image</label>
          <input class="form-control" type="file" id="profilePhotoFileUpload"></div>
        <div class="form-group">
          <label for="organizationField">Organizations</label>
          <select class="form-control" id="organizationField">
            <option value="">None</option>
          </select>
        </div>
        <div class="form-group">
          <label for="inputBio">Bio</label>
          <textarea rows="15" id="inputBio" class="form-control"></textarea>
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
          <input class="form-control" type="number" id="inputLinkedin" placeholder="xxxxxx">
          <span class="help-block">Enter the 'id=' user number from your Linkedin profile URL, (http://linkedin.com/profile/view?id=<strong>xxxxxx</strong>) </span>
        </div>
        <div class="form-group">
          <label for="inputGoggle">Google +</label>
          <input class="form-control" type="number" id="inputGoogle" placeholder="xxxxxxxxxxxx"></div>
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
          <ul id="interests" class="multi-list"></ul>
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
    <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <hr>
            <input type="submit" class="btn btn-success btn-lg btn-block" value="Save Guest" >
          </div>
        </div>
        <div class="col-md-6"></div>
      </div>
  </fieldset>
</form>

<?php require 'modal.php'; ?>

<script type="text/javascript">
  Parse.initialize("bzxMfkPDky6xy8G6rTjH39N2GG3U08G6NaSjPTLg", "WuQ4EQv7CW1IVX8wrdOnTOYeEUovU0vqWOGZxZfp");

  $(document).ready(function() {
      //create array for fact fields
      var factArray = [];
      var interestArray = [];
      var organizationObjectArray = [];

      //Pull Organizations and assign them to a select box
      var Organization = Parse.Object.extend("Organization");
      var query = new Parse.Query(Organization);

      //Grabs organizations and puts them in the organizationArray and populates the select box
      query.ascending("name").find({
        success: function(results) {
          organizationObjectArray = results;
          for(var i = 0; i < results.length; i++) {
            var 
              org = results[i],
              name = org.get('name'),
              id = org.get.id,
              select = $('#organizationField')
            ;

            select.append('<option value="' + i + '">' + name + '</option>');
          }
        },
        error: function(error) {
          alert("Error: " + error.code + " " + error.message);
        }
      });

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
          factField = $('#fact-field'),
          fact = factField.val(),
          list = $('ul#fact-list')
        ;

        list.html('');
        factArray = [];

        console.log(factArray);
    }
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

      //Form Submit Function
    $("#person-form").submit(function(e){
      //prevent form from submitting
      e.preventDefault();

      //create parse objects
      var Person = Parse.Object.extend("Person");
      var person = new Person();

      //set vars for fields
      var 
        name =  $("#inputName").val(),
        email =  $("#inputEmail").val(),
        phone =  $("#inputPhone").val(),
        bio =  $("#inputBio").val(),
        organizationIndex =  $("#organizationField option:selected").val(),
        facebook =  $("#inputFacebook").val(),
        google =  $("#inputGoogle").val(),
        linkedin =  $("#inputLinkedin").val(),
        twitter =  $("#inputTwitter").val()
      ;

      cleanName = name.replace('&', '').replace('*', '').replace("'", "");
      //avatar upload
      var fileUploadControl = $("#profilePhotoFileUpload")[0];
      if (fileUploadControl.files.length > 0) {
        var file = fileUploadControl.files[0];
        var photoName = cleanName + " Avatar";
       
        var avatar = new Parse.File(photoName, file);
      }

      var organization = organizationObjectArray[organizationIndex];
      person.set('organization', organization);
      person.set('title', organization.get('name'));

      //create object field for person
      person.set("name", name);
      person.set("email", email);
      person.set("phoneMain", phone);
      person.set("bio", bio);
      person.set("facts", factArray);
      person.set("profileImage", avatar);
      person.set("facebookName", facebook);
      person.set("twitterName", twitter);
      person.set("linkedinName", linkedin);
      person.set("googleName", google);
      person.set("interests", interestArray);

      //check if name and email are filled out, and if so save
      if (name=="" || email==""){
        $('#modal-fail').modal('show');
        $('#modal-fail .modal-body').html('<p>Nope, You need to at least fill in the <strong>Name</strong> and <strong>Email</strong> fields.</p><p>Lets try that one more time.</p>');
      } else{
        person.save(null, {
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
  });
</script>

<?php require 'footer.php'; ?>