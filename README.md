# WorkoutTracker
Workout Tracker group project for IT328 FullStack Web Development class. The Workout Tracker allows users to register 
and login, then they are allowed to add, delete, and edit their workouts for the past week. Project utilizes HTML5, CSS, Bootstrap, PHP, FatFree Framework, Javascript, Ajax.

## Authors
* Bridget Black - Github: https://github.com/BlackRabbit12 ::rabbit2::
* Chad Drennen - Github: https://github.com/MrDrennan ::smiley::

## Requirements

<details>
	<summary>Separates all database/business logic using the MVC pattern</summary>
  <p>We used the Model-View-Controller(MVC) pattern when we separated our files into: </p>
  
  + Model:
    + database.php
    + validation.php
  + Views:
    + header.html
    + footer.html
    + navbar.html
    + home.html
    + login.html
    + registration.html
  + Controller:
    + workout-controller.php
  + Classes:
    + user.php
    + premium-user.php
  + Styles:
    + login_register_styles.css
    + home.css
  + js:
    + validation.js
    + login_validation.js
    + toggle.js
    + home.js
  + index.php
      
</details>

<details>
	<summary>Routes all URLs and leverages a templating language using the Fat-Free framework</summary>
  <p>We implemented URL routing and templating, a few examples are:</p>
  
  + index.php
    
    ```
    php
    //Default/Home route
    $f3->route('GET /', function () {
    $GLOBALS['controller']->homeRoute();
    });
    ```
    
  + workout-controller.php
  
    ```
    php
    //go back to registration if user input was not validated and rerouted
        echo \Template::instance()->render('views/registration.html');
    ```
    
  + registration.html
  
    ```
    html
    <check if="{{ isset(@errors['first-name']) }}">
       <p>{{ @errors['first-name'] }}</p>
    </check>
    ```
    
</details>

<details>
	<summary>Has a clearly defined database layer using PDO and prepared statements. You should have at least two related tables</summary>
  <p>We used PDO and prepared statements, a few examples are:</p>
  
  + database.php
    
    ```
    php
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    ```
  + database.php, prepared statements AND single function involves related 3 tables
    
    ```
    function getWorkoutMuscleGroups($workoutId)
    {
        //query database
        $sql = 'SELECT muscle_group_name
                FROM workout
                    INNER JOIN workout_muscle_group ON workout.workout_id = workout_muscle_group.workout_id
                    INNER JOIN muscle_group ON workout_muscle_group.muscle_group_id = muscle_group.muscle_group_id
                WHERE workout.workout_id = :workoutId';
        //prepare statement
        $statement = $this->_dbh->prepare($sql);
        //bind parameters
        $statement->bindParam(':workoutId', $workoutId);
        //execute the statement
        $statement->execute();
        //return the result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    ```
	
</details>

<details>
	<summary>Data can be viewed, added, updated, and deleted</summary>
  <p>We can view, add, update, and delete items off of the workout day plan cards.</p>
  <img src="/images/addBtn.png" alt="Day Plan Table Add Button"/>
</details>

<details>
	<summary>Has a history of commits from both team members to a Git repository</summary>
	<p>We both commited and pair programmed, several commit messages include "PP with (partner's name)"
    View our commits bar graph here:</p>(https://github.com/BlackRabbit12/WorkoutTracker/pulse)
    
</details>

<details>
	<summary>Uses OOP, and defines multiple classes, including at least one inheritance relationship</summary>
  <p>We used OOP and defined multiple classes that use inheritance</p>
  
  + User Class
    + PremiumUser Class (inherits from User)
    
</details>

<details>
	<summary>Contains full Docblocks for all PHP files and follows PEAR standards</summary>
  <p>We used Docblocks for all of our PHP files and followed PEAR standards, here are a few examples:</p>
  
  + PEAR Standards
    
    ```
    php
    private $_firstName;
    private $_lastName;
    ```
    
  + Docblocks
    
    ```
    /**
     * Gets all columns from database for requested user IF the username being asked for exists in the database,
     * if it does not exist, the returned array is null.
     * @param $desiredUserName
     * @return array
     */
    function uniqueUserName($desiredUserName)
    ```
    
</details>

<details>
	<summary>Has full validation on the client side through JavaScript and server side through PHP</summary>
	<p>We have the red-box client side validation via JavaScript and the underline text error via PHP.
    Here is a visual example of each:</p>
  <p>JavaScript</p>
  <img src="/images/jsVal.png" alt="JS Validation"/>
  <p>Php</p>
  <img src="/images/phpVal.png" alt"PHP Validation"/>
  
</details>

<details>
	<summary>BONUS:  Incorporates Ajax that access data from a JSON file, PHP script, or API</summary>
  <p>We incorporated Ajax, here is an example:</p>
  
  <img src="/images/ajax.png" alt="Ajax Bonus"/>
</details>

<details>
	<summary>Diagram Images</summary>
	
### UML Class Diagram
<img src="/images/umlClassDiagram.png" alt="UML Class Diagram"/>

### ER Database Diagram
<img src="/images/WorkoutTrackerERDiagram.png" alt="ER Database Diagram"/>
	
</details>
