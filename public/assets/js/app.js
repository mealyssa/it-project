var myApp = angular.module('myApp', ['ngRoute']);

myApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider
    .when('/home', {
        controller: 'loginModalCtrl'
      })
    
  }]);

myApp.controller('loginModalCtrl', function ($scope) {
  alert(123);
});


