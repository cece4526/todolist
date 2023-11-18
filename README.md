# ToDo List OpenClassRoom

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/87f18cbb602b4f11bfb62522857ecfa5)](https://app.codacy.com/gh/cece4526/todolist?utm_source=github.com&utm_medium=referral&utm_content=cece4526/todolist&utm_campaign=Badge_Grade)

Voici le projet n°8 du parcours de développeur d'application PHP/Symfony chez [Openclassroom](https://openclassrooms.com/fr/)

### Installation
Pour installer ce projet veuillez suivre les indications en tapant dans votre terminal les commandes suivantes :
-  Cloner le projet
```sh
git clone https://github.com/cece4526/todolist.git
```

- Mettre a jour les dépendances du projet
```sh
composer install
```
Dans le fichier .env du projet.
Modifiez la ligne 27 pour mettre vos identifiants et le nom que vous souhaitez pour la base de données. Si vous utilisez un autre SGBDR veuillez vous référer à la documentation de [symfony](https://symfony.com/doc/current/doctrine.html)
```yaml
DATABASE_URL=mysql: //db_user:db_password@127.0.0.1:3306/db_name
```
- Création de la base de données
```sh
php bin/console doctrine:database:create
```

- Mise à jour des tables
```sh 
php bin/console doctrine:schema:update --force
```

Si vous souhaitez insérer des données fictives pour tester le projet, tapez la commande suivante.
```sh 
php bin/console doctrine:fixtures:load
```

Pour les tests, les couple identifiant/mot de passe se trouvent dans le dossier Fixtures. 

Enjoy !!
