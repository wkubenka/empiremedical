# Empire Medical Coding Eval
## Tic-Tac-Toe

### Feature Priority

Unfortunately, I do not have access to retiree Tic-Tac-Toe fans to do user research, 
and needed to make decisions myself about what would be important to them.

1. Playing Tic-Tac-Toe against the computer
    - Required for MVP and will be the base of all other features.
2. Leaderboards
    - This is required for MVP and dependent on the ability to play the game.
3. Hot seat play
    - This is being prioritized over Online play due to the target demographic. 
    The user base is likely to not be computer savvy, and I believe this will reduce the barrier 
    to play. This should be a fairly simple feature to build in once the base game is made.
4. Online play
    - Adding this feature to the product up to this point should be fairly simple using 
    Laravel Echo and Pusher
7. Tic-Tac-Toe-Royale
    - I believe this feature would be great for user experience, and would drive high user interaction. However, 
    development time would be higher than the previous features. First I would need to more research to understand
    the rules of Tic-Tac-Toe-Royale, then create the new components to meet the requirements.
6. The ability to spectate games in progress
    - Tic-Tac-Toe games are generally short, and are not typically conducive to spectators.
    This feature would be fairly simple to implement if online play is already a feature. Simply add the viewer to the
     pusher notifications, and modify the vue components to allow a spectator.
5. Watch previous matches
    - I do not imagine this will be a very used feature, as I picture this more as a replacement for the bridge group.
    I expect the users will be focused more on the next game than watching a previous one. 
    If this feature gets created, I would create a table containing every possible permutation of a game of Tic-Tac-Toe.
    Then save each game in a pivot table with the ids to the user(s) that played, what letter player 1 was, and the id
     to the permutation of the game. Then pass the data to a replay view on demand.

### Installation
```
git clone https://github.com/wkubenka/empiremedical.git
cd empiremedical
composer install
cp .env.example .env
php artisan key:generate
# configure db connection in .env
php artisan migrate --seed
npm install
php artisan serve
```

### Testing
TODO: Write frontend tests.
Run frontend tests with `npm test`

TODO: Write backend tests. 
Run backend tests with `phpunit` or `vendor/bin/phpunit`

