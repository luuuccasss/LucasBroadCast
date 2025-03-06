# 📢 LucasBroadcast - Plugin de Diffusion Automatique

LucasBroadcast est un plugin qui permet d'afficher automatiquement des messages personnalisés sur votre serveur. Offrant une gestion flexible, il permet d'envoyer des messages sous différentes formes, dans des mondes spécifiques et avec des sons intégrés.

---

## 🌟 Fonctionnalités Principales
✔️ Diffusion automatique de messages à intervalles définis
✔️ Support des messages en texte ou en popup
✔️ Gestion des annonces par monde ou globalement
✔️ Ajout et suppression dynamiques via commandes
✔️ Intégration de sons pour accompagner les annonces
✔️ Système de permissions pour un meilleur contrôle

---

## 📥 Installation
1. Téléchargez le fichier `.phar` du plugin.
2. Placez-le dans le dossier `plugins/` de votre serveur PocketMine-MP.
3. Démarrez le serveur pour générer automatiquement `config.yml`.
4. Modifiez `config.yml` pour configurer vos messages.

---

## ⚙️ Configuration (`config.yml`)

```yaml
messages:
  message1:
    time: 250
    message: "§aBienvenue sur le serveur ! Profitez de votre aventure."
    world: all
    popup: true
    sound: ClickSound
  message2:
    time: 300
    message: "§eRappel : Respectez les règles pour une meilleure expérience !"
    world: lobby
    popup: true
    sound: none
  message3:
    time: 400
    message: "§bAmusez-vous et bonne exploration !"
    world: none
    popup: false
    sound: none
```

🛠 **Explication des paramètres :**
- `time` : Délai (en secondes) avant l'affichage du message.
- `message` : Texte affiché aux joueurs.
- `world` : Monde ciblé (`all` pour tous les mondes).
- `popup` : `true` pour afficher en popup, `false` pour un message classique.
- `sound` : Son joué lors de l'affichage (`none` pour désactiver).

---

## 🔧 Commandes Disponibles

| Commande | Description |
|----------|------------|
| `/broadcast add <time> <world> <popup:true/false> <sound> <message>` | Ajoute un message à la liste |
| `/broadcast remove <id>` | Supprime un message enregistré |
| `/broadcast list` | Affiche la liste des messages actifs |

### 💡 Exemples d'utilisation
- Ajouter un message toutes les 30 secondes dans "spawn", sans popup et sans son :
  ```
  /broadcast add 30 spawn false none Profitez bien de votre aventure !
  ```
- Supprimer un message spécifique (`message1`) :
  ```
  /broadcast remove message1
  ```
- Voir tous les messages configurés :
  ```
  /broadcast list
  ```

---

## 🔑 Permissions
- `lucasbroadcast.command` : Autorise l'accès aux commandes `/broadcast`

---

## 👤 Auteur
- **luuuccasss** - Développement et support

📜 **Licence :** Ce plugin est distribué sous licence MIT, libre à modification et redistribution.

