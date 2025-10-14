# 🚀 Guide de Démarrage Rapide - Stagiaire

**Bienvenue !** Ce guide te permettra de commencer à utiliser l'API TGS Admin en **10 minutes**.

---

## 🎯 Objectif

Tu vas créer une page React qui affiche le concours E2C (Escapade à Cosplay) avec le jury et les participants.

---

## 📋 Prérequis

- Node.js installé
- Un éditeur de code (VSCode recommandé)
- Serveur Laravel démarré : `php artisan serve`

---

## ⚡ Étape 1 : Créer un projet React (2 min)

```bash
# Créer une nouvelle app React
npx create-react-app tgs-frontend
cd tgs-frontend

# Installer Axios
npm install axios

# Démarrer le serveur de dev
npm start
```

Ton app React devrait s'ouvrir sur `http://localhost:3000`

---

## 🔌 Étape 2 : Configurer l'API (1 min)

**Créer** `src/services/api.js` :

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/api/v1',
  headers: {
    'Accept': 'application/json',
  },
});

// Gestion des erreurs
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    console.error('❌ Erreur API:', error);
    return Promise.reject(error);
  }
);

export default api;
```

---

## 🎣 Étape 3 : Créer un hook personnalisé (2 min)

**Créer** `src/hooks/useFetch.js` :

```javascript
import { useState, useEffect } from 'react';
import api from '../services/api';

export const useFetch = (endpoint) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const response = await api.get(endpoint);

        if (response.success) {
          setData(response.data);
        } else {
          setError(response.message);
        }
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [endpoint]);

  return { data, loading, error };
};
```

---

## 🎨 Étape 4 : Créer le composant E2C (5 min)

**Créer** `src/components/E2CPage.jsx` :

```javascript
import React from 'react';
import { useFetch } from '../hooks/useFetch';
import './E2CPage.css';

const E2CPage = () => {
  const { data, loading, error } = useFetch('/salons/1/e2c');

  if (loading) {
    return <div className="loading">⏳ Chargement...</div>;
  }

  if (error) {
    return <div className="error">❌ Erreur : {error}</div>;
  }

  const { content, jury, participants } = data || {};

  return (
    <div className="e2c-page">
      {/* En-tête */}
      {content && (
        <header className="e2c-header">
          <img src={content.logo} alt="E2C Logo" />
          <h1>{content.title}</h1>
          <div dangerouslySetInnerHTML={{ __html: content.text }} />
        </header>
      )}

      {/* Jury */}
      <section className="jury-section">
        <h2>👥 Le Jury</h2>
        <div className="cards-grid">
          {jury?.map((member) => (
            <div key={member.id} className="card jury-card">
              <img src={member.featured_image} alt={member.title} />
              <h3>{member.title}</h3>
              <div dangerouslySetInnerHTML={{ __html: member.content }} />

              {/* Réseaux sociaux */}
              {member.social_links && (
                <div className="social-links">
                  {member.social_links.instagram && (
                    <a href={member.social_links.instagram} target="_blank" rel="noopener noreferrer">
                      📷 Instagram
                    </a>
                  )}
                </div>
              )}
            </div>
          ))}
        </div>
      </section>

      {/* Participants */}
      <section className="participants-section">
        <h2>🎭 Les Participants</h2>
        <div className="cards-grid">
          {participants?.map((participant) => (
            <div key={participant.id} className="card participant-card">
              <img src={participant.featured_image} alt={participant.title} />
              <h3>{participant.title}</h3>
              <div dangerouslySetInnerHTML={{ __html: participant.content }} />
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default E2CPage;
```

**Créer** `src/components/E2CPage.css` :

```css
.e2c-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.loading, .error {
  text-align: center;
  padding: 50px;
  font-size: 1.5rem;
}

.error {
  color: #dc3545;
}

/* En-tête */
.e2c-header {
  text-align: center;
  margin-bottom: 50px;
}

.e2c-header img {
  max-width: 300px;
  margin-bottom: 20px;
}

.e2c-header h1 {
  font-size: 2.5rem;
  color: #333;
  margin-bottom: 20px;
}

/* Sections */
section {
  margin-bottom: 60px;
}

section h2 {
  font-size: 2rem;
  text-align: center;
  margin-bottom: 30px;
  color: #FF6B35;
}

/* Grille de cartes */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
}

/* Cartes */
.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
}

.card img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 15px;
}

.card h3 {
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: #333;
}

/* Cartes spécifiques */
.jury-card {
  border-top: 4px solid #FFD700;
}

.participant-card {
  border-top: 4px solid #FF6B35;
}

/* Réseaux sociaux */
.social-links {
  display: flex;
  gap: 10px;
  margin-top: 15px;
  flex-wrap: wrap;
}

.social-links a {
  padding: 8px 15px;
  background: #f0f0f0;
  border-radius: 20px;
  text-decoration: none;
  color: #333;
  font-size: 0.9rem;
  transition: background 0.3s ease;
}

.social-links a:hover {
  background: #FF6B35;
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .cards-grid {
    grid-template-columns: 1fr;
  }

  .e2c-header h1 {
    font-size: 1.8rem;
  }

  section h2 {
    font-size: 1.5rem;
  }
}
```

**Modifier** `src/App.js` :

```javascript
import React from 'react';
import E2CPage from './components/E2CPage';
import './App.css';

function App() {
  return (
    <div className="App">
      <E2CPage />
    </div>
  );
}

export default App;
```

**Modifier** `src/App.css` :

```css
body {
  margin: 0;
  padding: 0;
  background: #f5f5f5;
}

.App {
  min-height: 100vh;
  padding: 20px;
}
```

---

## ✅ Étape 5 : Tester !

1. **Vérifie que le serveur Laravel tourne :**
   ```bash
   php artisan serve
   ```

2. **Vérifie que le serveur React tourne :**
   ```bash
   npm start
   ```

3. **Ouvre ton navigateur :**
   - React app : `http://localhost:3000`
   - Tu devrais voir la page E2C avec le jury et les participants !

---

## 🎉 Résultat attendu

Tu devrais voir :
- ✅ Un header avec le logo et titre E2C
- ✅ Section "Le Jury" avec 2 membres
- ✅ Section "Les Participants" avec 2 cosplayers
- ✅ Des cartes stylisées avec hover effect
- ✅ Des liens vers les réseaux sociaux

---

## 🐛 Problèmes courants

### ❌ Erreur CORS ?

Ajouter dans Laravel `config/cors.php` :

```php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:3000'],
```

Puis :
```bash
php artisan config:clear
```

### ❌ Erreur 404 ?

Vérifie l'URL de l'API :
```javascript
// ✅ Correct
baseURL: 'http://127.0.0.1:8000/api/api/v1'

// ❌ Incorrect
baseURL: 'http://127.0.0.1:8000/api/v1'
```

### ❌ Pas de données ?

Lance le seeder :
```bash
php artisan db:seed --class=SimpleApiSeeder
```

---

## 🚀 Aller plus loin

### 1. Ajouter une recherche

```javascript
const [search, setSearch] = useState('');

const { data } = useFetch(`/salons/1/e2c?search=${search}`);

// Dans le JSX
<input
  type="text"
  value={search}
  onChange={(e) => setSearch(e.target.value)}
  placeholder="Rechercher..."
/>
```

### 2. Afficher la galerie

```javascript
{participant.gallery?.map((image, i) => (
  <img key={i} src={image} alt={`Gallery ${i}`} />
))}
```

### 3. Ajouter React Router

```bash
npm install react-router-dom
```

```javascript
// Voir la documentation complète pour un exemple complet
```

---

## 📚 Ressources

- **Documentation complète :** `docs/API_DOCUMENTATION.md`
- **Swagger UI :** http://127.0.0.1:8000/api/documentation
- **Tous les endpoints :** Voir la doc complète

---

## 💡 Conseils

1. **Utilise les DevTools** de Chrome/Firefox pour inspecter les requêtes
2. **Console.log** tes données pour comprendre la structure
3. **Teste d'abord l'API** avec Swagger avant de coder en React
4. **Lis la documentation complète** pour des exemples avancés

---

## 🎯 Challenge bonus

Une fois que ça fonctionne, essaie de :
1. ✅ Ajouter un loader animé
2. ✅ Créer une page de détail pour chaque participant
3. ✅ Afficher la galerie d'images en modal
4. ✅ Ajouter des filtres (jury/participants)
5. ✅ Implémenter React Query pour le cache

---

**Bon courage ! 🚀**

Si tu as des questions, n'hésite pas à consulter la documentation complète ou à demander de l'aide.

---

**Dernière mise à jour :** 12 octobre 2025
