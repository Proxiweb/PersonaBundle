var signinLink = document.getElementById('signin');
if (signinLink) {
  signinLink.onclick = function() { navigator.id.request(); };
};

var signoutLink = document.getElementById('signout');
if (signoutLink) {
  signoutLink.onclick = function() { navigator.id.logout(); };
};

if (window.localStorage.getItem('email_login')) {
  var currentUser = window.localStorage.getItem('email_login');
} else {
  var currentUser = null;  
}

navigator.id.watch({
  loggedInUser: currentUser,
  onlogin: function(assertion) {

    $.ajax({
      type: 'GET',
      url: '/app_dev.php/persona/login',
      data: {assertion: assertion},
      success: function(res, status, xhr) { 
	console.log("login success");
	window.localStorage.setItem('email_login',res.email);
	window.location.reload();
        	
      },
      error: function(xhr, status, err) { 
	console.log("login failure " + err);
      }
    });
  },
  onlogout: function() {
    $.ajax({
      type: 'GET',
      url: '/app_dev.php/persona/logout',
      success: function(res, status, xhr) { 
	currentUser = null; 
	window.localStorage.removeItem('email_login');
	window.location.reload(); },
      error: function(xhr, status, err) { console.log("logout failure" + err); }
    });
  }
});
