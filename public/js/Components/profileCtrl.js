app.controller('profileCtrl', ['$scope', '$window', function ($scope, $window) {
    keepHistory($window.user.id,"profile",dtJsToDtDB(new Date()));
    $scope.prefix = user.prefix;
    $scope.fname = user.fname_th;
    $scope.lname = user.lname_th;
    $scope.personalID = user.personal_id;
    $scope.stuID = user.stu_id;
    $scope.faculty = user.faculty;
    $scope.department = user.department;
    $scope.email = user.email;
}]);