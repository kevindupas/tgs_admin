# 📚 Documentation API TGS Admin

Bienvenue dans la documentation de l'API TGS Admin !

## 📖 Documentation disponible

### 1. **Documentation Markdown complète**
📄 [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

Documentation exhaustive avec :
- ✅ Tous les endpoints détaillés
- ✅ Exemples de requêtes/réponses
- ✅ Exemples complets en React (hooks, composants, services)
- ✅ Composant E2C complet avec CSS
- ✅ Bonnes pratiques
- ✅ Gestion des erreurs

**Parfait pour :** Votre stagiaire qui développe en React

---

### 2. **Documentation Swagger Interactive**
🌐 **URL:** `http://127.0.0.1:8000/api/documentation`

Documentation interactive générée automatiquement avec Swagger UI :
- ✅ Interface web élégante
- ✅ Testez les endpoints directement dans le navigateur
- ✅ Schémas de données détaillés
- ✅ Exemples de réponses

**Comment y accéder :**
1. Démarrez votre serveur Laravel : `php artisan serve`
2. Ouvrez votre navigateur : `http://127.0.0.1:8000/api/documentation`

---

## 🚀 Démarrage rapide

### Prérequis
- PHP 8.1+
- Laravel 11
- Base de données configurée

### Installation

1. **Cloner le projet**
```bash
git clone <votre-repo>
cd tgs_admin
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Migrer et seeder la base**
```bash
php artisan migrate:fresh
php artisan db:seed --class=SimpleApiSeeder
```

5. **Lancer le serveur**
```bash
php artisan serve
```

6. **Accéder à l'API**
- API : `http://127.0.0.1:8000/api/api/v1`
- Swagger : `http://127.0.0.1:8000/api/documentation`

---

## 🎯 Exemples rapides

### Récupérer tous les salons
```bash
curl http://127.0.0.1:8000/api/api/v1/salons
```

### Récupérer les données E2C d'un salon
```bash
curl http://127.0.0.1:8000/api/api/v1/salons/1/e2c
```

### Rechercher dans E2C
```bash
curl "http://127.0.0.1:8000/api/api/v1/salons/1/e2c?search=zelda"
```

---

## 📦 Structure de la documentation

```
docs/
├── README.md                    # Ce fichier
├── API_DOCUMENTATION.md         # Documentation complète
└── examples/                    # (à venir) Exemples supplémentaires
```

---

## 🔧 Régénérer la documentation Swagger

Si vous modifiez les annotations dans les contrôleurs :

```bash
php artisan l5-swagger:generate
```

---

## 💡 Pour votre stagiaire

### Ressources recommandées

1. **Lire en priorité :**
   - [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - Section "E2C"
   - Exemples React complets dans la documentation

2. **Tester l'API :**
   - Utiliser Swagger UI : `http://127.0.0.1:8000/api/documentation`
   - Ou Postman/Insomnia avec la collection générée

3. **Code React prêt à l'emploi :**
   - Hook `useFetch` personnalisé
   - Composant E2C complet avec CSS
   - Service API complet avec tous les endpoints
   - Exemples avec React Query

### Points clés à retenir

✅ **L'API E2C a été simplifiée :**
- Avant : 5 endpoints différents
- Maintenant : **2 endpoints seulement**
  - `GET /salons/{id}/e2c` → Tout récupérer en un seul call
  - `GET /salons/{id}/e2c/{articleId}` → Détail d'un article

✅ **Format de réponse uniforme :**
```json
{
  "success": true,
  "data": { ... }
}
```

✅ **Pas d'authentification requise**

✅ **Toutes les données sont en lecture seule** (GET uniquement)

---

## 🐛 Dépannage

### Swagger ne fonctionne pas ?

1. Vérifier que L5-Swagger est installé :
```bash
composer require darkaonline/l5-swagger
```

2. Publier la configuration :
```bash
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
```

3. Générer la documentation :
```bash
php artisan l5-swagger:generate
```

4. Vider le cache :
```bash
php artisan config:clear
php artisan route:clear
```

### Erreur 404 sur les routes API ?

Vérifier que les routes sont bien préfixées avec `api/api/v1` :
```
❌ Incorrect : http://127.0.0.1:8000/api/v1/salons/1/e2c
✅ Correct   : http://127.0.0.1:8000/api/api/v1/salons/1/e2c
```

### Pas de données ?

Lancer le seeder :
```bash
php artisan db:seed --class=SimpleApiSeeder
```

---

## 📞 Support

- **Email :** support@tgs.com
- **Issues :** [GitHub Issues](https://github.com/tgs/issues)

---

## 📝 Convertir en PDF

Pour convertir la documentation Markdown en PDF :

### Méthode 1 : VSCode + Extension
1. Installer l'extension "Markdown PDF"
2. Ouvrir `API_DOCUMENTATION.md`
3. Cmd/Ctrl + Shift + P → "Markdown PDF: Export (pdf)"

### Méthode 2 : En ligne
1. Aller sur https://www.markdowntopdf.com/
2. Copier/coller le contenu de `API_DOCUMENTATION.md`
3. Télécharger le PDF

### Méthode 3 : Pandoc (recommandé)
```bash
# Installer pandoc
brew install pandoc  # macOS
# ou
sudo apt install pandoc  # Linux

# Générer le PDF
pandoc API_DOCUMENTATION.md -o API_DOCUMENTATION.pdf --pdf-engine=xelatex
```

---

**Dernière mise à jour :** 12 octobre 2025
**Version de l'API :** 1.0.0
