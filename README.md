LostMovie 1.1.0
===============

Unified way to search movies online. Currently, only SensCritique is supported.

Install
-------

```
composer require pyrsmk/lostmovie
```

SensCritique
------------

```php
$senscritique = new LostMovie\SensCritique();

$data = $senscritique->search('star wars 7');

var_dump($data);
```

It will return :

```
array(7) {
  ["title"] => string(29) "Star Wars : The Force Awakens"
  ["year"] => string(4) "2015"
  ["duration"] => string(10) "2 h 16 min"
  ["genres"] => array(3) {
    [0] => string(6) "action"
    [1] => string(8) "aventure"
    [2] => string(11) "fantastique"
  }
  ["rating"] => string(3) "6.8"
  ["poster"] => string(89) "https://media.senscritique.com/media/000014930137/160/Star_Wars_Le_Reveil_de_la_Force.jpg"
  ["synopsis"] => string(148) "Septième épisode de la saga Star Wars et premier d'une nouvelle trilogie, dont les événements se déroulent trente ans après Le retour du Jedi."
}
```

License
-------

[MIT](http://dreamysource.mit-license.org).
