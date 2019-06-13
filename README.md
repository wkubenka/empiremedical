# Empire Medical Coding Eval
## Tic-Tac-Toe

### Feature Priority

Unfortunately, I do not have access to retiree Tic-Tac-Toe fans to do user research, 
and needed to make decisions myself about what would be important to them.
Here are how I ranked the features requested, why I ranked them where I did, and the status of them.

1. Playing Tic-Tac-Toe against the computer
    - Required for MVP and will be the base of all other features.
    - Complete
2. Leaderboards
    - This is required for MVP and dependent on the ability to play the game.
    - Complete
3. Hot seat play
    - This is being prioritized over Online play due to the target demographic. 
    The user base is likely to not be computer savvy, and I believe this will reduce the barrier 
    to play. This should be a fairly simple feature to build in once the base game is made.
    - Complete
4. Online play
    - Adding this feature to the product up to this point should be fairly simple using 
    Laravel Echo and Pusher
    - Incomplete
7. Tic-Tac-Toe-Royale
    - I believe this feature would be great for user experience, and would drive high user interaction. However, 
    development time would be higher than the previous features. First I would need to research and understand
    the rules of Tic-Tac-Toe-Royale, then create the new components to meet the requirements.
    - Incomplete
6. The ability to spectate games in progress
    - Tic-Tac-Toe games are generally short, and are not typically conducive to spectators.
    This feature would be fairly simple to implement if online play is already a feature. Simply add the viewer to the
    Pusher notifications, and modify the vue components to allow a spectator.
     - Incomplete
5. Watch previous matches
    - I do not imagine this will be a very used feature, as I picture this more as a replacement for a bridge group.
    In that I expect the users will be focused more on the next game than watching a previous one. 
    If this feature gets created, I would create a table containing every possible permutation of a game of Tic-Tac-Toe.
    Then save each game in a pivot table with the ids to the user(s) that played, what letter player 1 was, and the id
     to the permutation of the game. Then pass the data to a replay viewer on demand.
     - Incomplete

### Installation
```
git clone https://github.com/wkubenka/empiremedical.git
cd empiremedical
composer install
cp .env.example .env
php artisan key:generate
# configure db connection in .env
php artisan migrate --seed
npm install #Not need if not doing development
php artisan serve
```

### Testing
I made a decision to not write automated test for this application. Given the simplicity of the project, combined with the  
future plans for this project (none at all), I did not believe them to be worth the development overhead. 

Testing McTestface is a user that gets created for testing when you seed the db. Below are the credentials for the account.

```
email: test@example.com
password: password
```

### Significant Files
- game.blade.php
    - Is the main page for the app. Holds the two Vue "components" that are the application. (I put components in quotes because I did not 
    attempt to split them into actual vue components.)
    
- app.js
    - Contains the code side of the the Vue components, imports Vue and Laravel's default bootstrap.js
    
- bootstrap.js
    - Brings in dependencies as default from Laravel. I wanted to modify this to remove Bootstrap and jQuery, which I 
    believe to be bloated for the needs of this app, but then I remembered that I would need to restyle all the auth pages
    and left it in.

- app.scss
    - Contains the custom Sass I wrote for the app and also imports Bootstrap for reasons listed above.
    
- UsersTableSeeder.php
    - Creates Testing McTestface and 50 other users to populate the leaderboard.
    
- GameController.php
    - Complete method increments the user's game and/or win variable. It currently just accepts that the client
    is telling the truth about a game being played, and the outcome of the game. This is less than ideal, and the server
    needs a way to validate that the game was played if this leaderboard is ever going to mean anything. Otherwise someone 
    could just hit the endpoint a few hundred times and climb into first.
    
- LeaderboardController.php
    - Index method returns the a paginated 10 users ranked by the total number of wins. (Note: the front end does not do 
    anything with the pagination ability.)
    
- User.php
    - WinPercent is a calculated attribute on user, and it is appended when Users are cast to an array. I expected 
    that I would need to do that calculation a lot more than it turned out I needed to.
