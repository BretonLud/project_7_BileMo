# project_7_BileMo

### Prérequis

***

Installer docker

### Installation du projet

***

Pour commencer on clone le projet:

<pre><code><strong>git clone git@github.com:BretonLud/project_7_BileMo.git
</strong></code></pre>

Ensuite il faut se rendre dans le dossier du projet

```
cd project_7_BileMo
```

Créer un fichier la racine du projet .env.local

```
touch .env.local
```

Paramétrer les variables d'environnement avec vos informations: (le smtp paramétré est celui de MailHog)

```
MARIADB_USER=username
MARIADB_PASSWORD=password
MARIADB_ROOT_PASSWORD=root
DATABASE_URL="mysql://username:password@database:3306/bilemo"
```

Lancer les containers docker avec les 2 commandes suivantes :

```
 docker compose build
 docker compose up -d
```

Insérer les données dans la base de données juste en changeant l'username et le password :

```
docker exec -i bilemo_database /usr/bin/mariadb -u username --password=password bilemo < dump.sql
```

Vous avez accès site sur l'adresse suivante :

```
localhost:8888/api
```

Vous avez accès à 2 comptes : 

```
email: admin@example.com
password: admin

et 

email: client@example.com
password: client

```

Amusez vous ensuite :)