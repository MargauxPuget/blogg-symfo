# Création du projet symfony(skeleton)

Création de la structure minimale d'un projet symfony et supressiondu sur dossier inutile
```bash
composer create-project symfony/skeleton blog
mv blog/* blog/.* .
rmdir blog/
```

Ajout de touts les composants nécéssaires au projet
```bash
composer require annotations
composer require twig
composer require symfony/asset
composer require --dev symfony/profiler-pack
composer require --dev symfony/var-dumper
composer require --dev symfony/debug-bundle
composer require --dev symfony/maker-bundle
composer require symfony/orm-pack
composer require --dev orm-fixtures

```

Pour le `orm-pack` ne pas oublier de créer çà la racine et de remplir le fichier .env.local
```text
DATABASE_URL="mysql://blogg:blogg@127.0.0.1:3306/blogg?serverVersion=mariadb-10.3.25"
```

# Création de la BDD avec doctrine : blogg

# Création des fixtures

# création des routes

## Route pour tous les posts

## Route pour un posts en fonction de son id

## Route pour mettre à jour un post

## Route pour supprimer un post

## Route pour créer un post
