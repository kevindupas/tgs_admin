# 📚 Documentation API - Toulouse Game Show Admin

**Version:** 1.0.0
**Base URL:** `http://votre-domaine.com/api/api/v1`
**Format de réponse:** JSON
**Méthode HTTP:** GET uniquement

---

## 📖 Table des matières

1. [Introduction](#introduction)
2. [Configuration](#configuration)
3. [Authentification](#authentification)
4. [Gestion des erreurs](#gestion-des-erreurs)
5. [Endpoints](#endpoints)
   - [Salons](#salons)
   - [Articles](#articles)
   - [Catégories](#catégories)
   - [Disponibilités](#disponibilités)
   - [FAQs](#faqs)
   - [Infos Pratiques](#infos-pratiques)
   - [Partenaires](#partenaires)
   - [Prix des Billets](#prix-des-billets)
   - [E2C (Escapade à Cosplay)](#e2c-escapade-à-cosplay)
   - [Pages Spéciales](#pages-spéciales)
   - [Tags](#tags)
   - [Contenus Billetterie](#contenus-billetterie)
6. [Exemples avec React](#exemples-avec-react)
7. [Bonnes pratiques](#bonnes-pratiques)

---

## 🎯 Introduction

Cette API REST permet d'accéder aux données du système TGS Admin. Toutes les routes sont **publiques** et accessibles en **lecture seule** (GET uniquement).

### Caractéristiques
- 🔓 **Pas d'authentification requise**
- 📖 **Lecture seule** (GET uniquement)
- 🎨 **Format JSON** pour toutes les réponses
- ⚡ **Performances optimisées** avec eager loading
- 🔍 **Filtrage et recherche** disponibles sur certains endpoints

---

## ⚙️ Configuration

### URL de base
```
Production: https://votre-domaine.com/api/api/v1
Développement: http://127.0.0.1:8000/api/api/v1
```

### Headers recommandés
```http
Accept: application/json
Content-Type: application/json
```

---

## 🔐 Authentification

**Aucune authentification n'est requise.** Toutes les routes sont publiques.

---

## ❌ Gestion des erreurs

### Format des erreurs

```json
{
  "success": false,
  "message": "Description de l'erreur"
}
```

### Codes HTTP
- `200` : Succès
- `404` : Ressource non trouvée
- `500` : Erreur serveur

---

## 🔌 Endpoints

---

## 🏢 Salons

### Liste des salons
```http
GET /salons
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Toulouse Game Show 2025",
      "edition": "2025",
      "edition_color": "#FF6B35",
      "event_date": "2025-11-15",
      "logo": "url_logo",
      "event_logo": "url_event_logo",
      "countdown": "2025-11-15T10:00:00.000000Z",
      "ticket_link": "https://billetterie.tgs.com",
      "website_link": "https://toulousegameshow.fr",
      "park_address": "Parc des Expositions, Toulouse",
      "halls": "3 halls",
      "scenes": "2 scènes",
      "invites": "50+ invités",
      "e2c": true,
      "created_at": "2025-10-12T20:08:58.000000Z",
      "updated_at": "2025-10-12T20:08:58.000000Z"
    }
  ]
}
```

### Détail d'un salon
```http
GET /salons/{id}
```

**Paramètres:**
- `id` (integer, requis) : ID du salon

**Réponse:** Même structure que la liste mais avec un seul objet.

### Statistiques d'un salon
```http
GET /salons/{id}/stats
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "total_articles": 10,
    "total_categories": 5,
    "total_partners": 8,
    "total_faqs": 12
  }
}
```

---

## 📰 Articles

### Liste des articles d'un salon
```http
GET /salons/{salon_id}/articles
```

**Paramètres de requête (optionnels):**
- `category` (integer) : Filtrer par catégorie
- `availability` (integer) : Filtrer par disponibilité
- `featured` (boolean) : Articles mis en avant
- `search` (string) : Recherche dans le titre

**Exemple:**
```http
GET /salons/1/articles?featured=true&category=1
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Kayane - Championne Fighting Games",
      "slug": "kayane-championne-fighting-games",
      "content": "<h2>Kayane</h2><p>Description...</p>",
      "featured_image": "https://...",
      "gallery": ["url1", "url2"],
      "videos": ["url_video"],
      "social_links": {
        "instagram": "https://instagram.com/...",
        "twitter": "https://twitter.com/..."
      },
      "pivot": {
        "salon_id": 1,
        "category_id": 1,
        "availability_id": 1,
        "is_featured": true,
        "is_published": true,
        "published_at": "2025-10-12T20:08:58.000000Z",
        "content_salon": "<p>Contenu spécifique au salon</p>",
        "display_order": 1
      },
      "category": {
        "id": 1,
        "name": "Invités"
      },
      "availability": {
        "id": 1,
        "name": "Vendredi"
      }
    }
  ]
}
```

### Détail d'un article
```http
GET /salons/{salon_id}/articles/{article_id}
```

### Articles mis en avant
```http
GET /salons/{salon_id}/articles/featured
```

### Articles avec planning
```http
GET /salons/{salon_id}/articles/scheduled
```

### Articles publiés
```http
GET /salons/{salon_id}/articles/published
```

---

## 📁 Catégories

### Liste des catégories d'un salon
```http
GET /salons/{salon_id}/categories
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Invités",
      "salon_id": 1,
      "created_at": "2025-10-12T20:08:58.000000Z"
    }
  ]
}
```

### Détail d'une catégorie
```http
GET /salons/{salon_id}/categories/{category_id}
```

### Articles d'une catégorie
```http
GET /salons/{salon_id}/categories/{category_id}/articles
```

---

## 📅 Disponibilités

### Liste des disponibilités d'un salon
```http
GET /salons/{salon_id}/availabilities
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Vendredi",
      "salon_id": 1
    }
  ]
}
```

### Détail d'une disponibilité
```http
GET /salons/{salon_id}/availabilities/{availability_id}
```

### Articles d'une disponibilité
```http
GET /salons/{salon_id}/availabilities/{availability_id}/articles
```

---

## ❓ FAQs

### Liste des FAQs d'un salon
```http
GET /salons/{salon_id}/faqs
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Quels sont les horaires ?",
      "content": "<p>Le salon est ouvert de 10h à 19h</p>",
      "order": 1,
      "salon_id": 1
    }
  ]
}
```

### Détail d'une FAQ
```http
GET /salons/{salon_id}/faqs/{faq_id}
```

---

## 🗺️ Infos Pratiques

### Liste des infos pratiques d'un salon
```http
GET /salons/{salon_id}/practical-infos
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Transport",
      "mini_content": "Métro ligne B",
      "content": "<p>Métro ligne B - arrêt Parc des Expos</p>",
      "icon": "🚇",
      "color": "#0066CC",
      "image": "url_image",
      "order": 1,
      "salon_id": 1
    }
  ]
}
```

### Détail d'une info pratique
```http
GET /salons/{salon_id}/practical-infos/{practical_info_id}
```

---

## 🤝 Partenaires

### Liste des partenaires d'un salon
```http
GET /salons/{salon_id}/partners
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Nintendo",
      "logo": "https://via.placeholder.com/300x150",
      "sponsor": true,
      "salon_id": 1
    }
  ]
}
```

### Détail d'un partenaire
```http
GET /salons/{salon_id}/partners/{partner_id}
```

---

## 🎟️ Prix des Billets

### Liste des tarifs d'un salon
```http
GET /salons/{salon_id}/ticket-prices
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Pass 1 Jour",
      "price": "15.00",
      "sold_out": false,
      "contents": [
        "Accès à toutes les zones",
        "Entrée pour 1 journée"
      ],
      "salon_id": 1
    }
  ]
}
```

### Détail d'un tarif
```http
GET /salons/{salon_id}/ticket-prices/{ticket_price_id}
```

---

## 🎭 E2C (Escapade à Cosplay)

### ⭐ Index E2C - Tout en un seul call
```http
GET /salons/{salon_id}/e2c
```

**Description:** Récupère **toutes les données E2C** en un seul appel : contenu, jury et participants.

**Paramètres de requête (optionnels):**
- `search` (string) : Recherche dans les titres des articles E2C

**Réponse:**
```json
{
  "success": true,
  "data": {
    "content": {
      "id": 1,
      "logo": "https://via.placeholder.com/300x200",
      "title": "Escapade à Cosplay 2025",
      "text": "<h2>Concours Cosplay</h2><p>Participez au concours !</p>",
      "salon_id": 1
    },
    "jury": [
      {
        "id": 1,
        "title": "Marie - Jury",
        "slug": "marie-jury",
        "content": "<p>Cosplayeuse professionnelle depuis 10 ans</p>",
        "featured_image": "https://via.placeholder.com/600x800",
        "gallery": ["url1", "url2"],
        "videos": ["url_video"],
        "social_links": {
          "instagram": "https://instagram.com/marie",
          "facebook": "https://facebook.com/marie"
        },
        "is_jury": true,
        "display_order": 1,
        "salon_id": 1
      }
    ],
    "participants": [
      {
        "id": 3,
        "title": "Participant 1 - Zelda",
        "slug": "participant-1-zelda",
        "content": "<p>Cosplay de Zelda BOTW</p>",
        "featured_image": "https://via.placeholder.com/600x800",
        "gallery": ["url1"],
        "videos": null,
        "social_links": {
          "instagram": "https://instagram.com/zelda"
        },
        "is_jury": false,
        "display_order": 1,
        "salon_id": 1
      }
    ]
  }
}
```

### Détail d'un article E2C
```http
GET /salons/{salon_id}/e2c/{e2c_article_id}
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Marie - Jury",
    "slug": "marie-jury",
    "content": "<p>Cosplayeuse professionnelle</p>",
    "featured_image": "https://...",
    "gallery": ["url1", "url2"],
    "videos": ["url_video"],
    "social_links": {
      "instagram": "https://instagram.com/marie",
      "twitter": "https://twitter.com/marie"
    },
    "is_jury": true,
    "display_order": 1,
    "salon_id": 1
  }
}
```

---

## 📄 Pages Spéciales

### Presse
```http
GET /salons/{salon_id}/presse
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_title": "Espace Presse",
    "first_content": "<p>Accréditations presse</p>",
    "second_title": "Kit Presse",
    "second_content": "<p>Téléchargez le kit</p>",
    "third_title": "Contact",
    "third_content": "<p>presse@tgs.com</p>",
    "ticket_presse_link": "https://tgs.com/presse",
    "salon_id": 1
  }
}
```

### Photos des invités
```http
GET /salons/{salon_id}/photos-invites
```

### Devenir exposant
```http
GET /salons/{salon_id}/become-exhibitor
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Devenir Exposant",
    "content": "<p>Réservez votre stand</p>",
    "salon_id": 1
  }
}
```

### Devenir bénévole
```http
GET /salons/{salon_id}/become-staff
```

---

## 🏷️ Tags

### Liste des tags
```http
GET /tags
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Gaming"
    }
  ]
}
```

### Recherche de tags
```http
GET /tags/search?q=gaming
```

### Détail d'un tag
```http
GET /tags/{tag_id}
```

---

## 🎫 Contenus Billetterie

### Liste des contenus billetterie
```http
GET /ticket-contents
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Billetterie Générale"
    }
  ]
}
```

### Recherche dans les contenus
```http
GET /ticket-contents/search?q=conditions
```

### Détail d'un contenu billetterie
```http
GET /ticket-contents/{ticket_content_id}
```

---

## ⚛️ Exemples avec React

### 1. Configuration de base avec Axios

```bash
npm install axios
```

**`src/services/api.js`**
```javascript
import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api/api/v1';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Intercepteur pour gérer les erreurs
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    console.error('API Error:', error);
    return Promise.reject(error);
  }
);

export default api;
```

---

### 2. Hook personnalisé pour fetch

**`src/hooks/useFetch.js`**
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

### 3. Récupérer tous les salons

**`src/components/SalonList.jsx`**
```javascript
import React from 'react';
import { useFetch } from '../hooks/useFetch';

const SalonList = () => {
  const { data: salons, loading, error } = useFetch('/salons');

  if (loading) return <div>Chargement des salons...</div>;
  if (error) return <div>Erreur : {error}</div>;

  return (
    <div className="salon-list">
      <h1>Salons</h1>
      {salons?.map((salon) => (
        <div key={salon.id} className="salon-card">
          <h2>{salon.name}</h2>
          <p>Edition : {salon.edition}</p>
          <p>Date : {new Date(salon.event_date).toLocaleDateString('fr-FR')}</p>
          {salon.e2c && <span className="badge">E2C Activé</span>}
        </div>
      ))}
    </div>
  );
};

export default SalonList;
```

---

### 4. Afficher les articles d'un salon

**`src/components/ArticleList.jsx`**
```javascript
import React, { useState } from 'react';
import { useFetch } from '../hooks/useFetch';

const ArticleList = ({ salonId }) => {
  const [categoryFilter, setCategoryFilter] = useState('');
  const [searchTerm, setSearchTerm] = useState('');

  // Construction dynamique de l'URL avec filtres
  const buildUrl = () => {
    let url = `/salons/${salonId}/articles?`;
    const params = new URLSearchParams();

    if (categoryFilter) params.append('category', categoryFilter);
    if (searchTerm) params.append('search', searchTerm);

    return url + params.toString();
  };

  const { data: articles, loading, error } = useFetch(buildUrl());

  if (loading) return <div>Chargement des articles...</div>;
  if (error) return <div>Erreur : {error}</div>;

  return (
    <div className="article-list">
      {/* Filtres */}
      <div className="filters">
        <input
          type="text"
          placeholder="Rechercher..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
        <select
          value={categoryFilter}
          onChange={(e) => setCategoryFilter(e.target.value)}
        >
          <option value="">Toutes les catégories</option>
          <option value="1">Invités</option>
          <option value="2">Exposants</option>
        </select>
      </div>

      {/* Liste des articles */}
      <div className="articles-grid">
        {articles?.map((article) => (
          <div key={article.id} className="article-card">
            <img src={article.featured_image} alt={article.title} />
            <h3>{article.title}</h3>
            <div
              className="content"
              dangerouslySetInnerHTML={{ __html: article.pivot.content_salon }}
            />
            <div className="meta">
              <span className="category">{article.category?.name}</span>
              <span className="availability">{article.availability?.name}</span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ArticleList;
```

---

### 5. **⭐ Composant E2C complet**

**`src/components/E2C/E2CPage.jsx`**
```javascript
import React from 'react';
import { useFetch } from '../hooks/useFetch';
import './E2CPage.css';

const E2CPage = ({ salonId }) => {
  const { data: e2cData, loading, error } = useFetch(`/salons/${salonId}/e2c`);

  if (loading) {
    return (
      <div className="e2c-loading">
        <div className="spinner"></div>
        <p>Chargement du concours E2C...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="e2c-error">
        <p>⚠️ Erreur de chargement : {error}</p>
      </div>
    );
  }

  const { content, jury, participants } = e2cData || {};

  return (
    <div className="e2c-page">
      {/* En-tête E2C */}
      {content && (
        <header className="e2c-header">
          <img src={content.logo} alt="E2C Logo" className="e2c-logo" />
          <h1>{content.title}</h1>
          <div
            className="e2c-description"
            dangerouslySetInnerHTML={{ __html: content.text }}
          />
        </header>
      )}

      {/* Section Jury */}
      <section className="e2c-section jury-section">
        <h2>👥 Le Jury</h2>
        <div className="e2c-grid">
          {jury?.map((member) => (
            <div key={member.id} className="e2c-card jury-card">
              <img
                src={member.featured_image}
                alt={member.title}
                className="e2c-card-image"
              />
              <h3>{member.title}</h3>
              <div
                className="e2c-card-content"
                dangerouslySetInnerHTML={{ __html: member.content }}
              />

              {/* Réseaux sociaux */}
              {member.social_links && (
                <div className="social-links">
                  {member.social_links.instagram && (
                    <a
                      href={member.social_links.instagram}
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      📷 Instagram
                    </a>
                  )}
                  {member.social_links.twitter && (
                    <a
                      href={member.social_links.twitter}
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      🐦 Twitter
                    </a>
                  )}
                </div>
              )}
            </div>
          ))}
        </div>
      </section>

      {/* Section Participants */}
      <section className="e2c-section participants-section">
        <h2>🎭 Les Participants</h2>
        <div className="e2c-grid">
          {participants?.map((participant) => (
            <div key={participant.id} className="e2c-card participant-card">
              <img
                src={participant.featured_image}
                alt={participant.title}
                className="e2c-card-image"
              />
              <h3>{participant.title}</h3>
              <div
                className="e2c-card-content"
                dangerouslySetInnerHTML={{ __html: participant.content }}
              />

              {/* Galerie */}
              {participant.gallery && participant.gallery.length > 0 && (
                <div className="gallery">
                  {participant.gallery.map((image, index) => (
                    <img
                      key={index}
                      src={image}
                      alt={`${participant.title} - ${index + 1}`}
                      className="gallery-image"
                    />
                  ))}
                </div>
              )}

              {/* Vidéos */}
              {participant.videos && participant.videos.length > 0 && (
                <div className="videos">
                  {participant.videos.map((video, index) => (
                    <a
                      key={index}
                      href={video}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="video-link"
                    >
                      🎥 Voir la vidéo {index + 1}
                    </a>
                  ))}
                </div>
              )}

              {/* Réseaux sociaux */}
              {participant.social_links && (
                <div className="social-links">
                  {Object.entries(participant.social_links).map(([platform, url]) => (
                    <a
                      key={platform}
                      href={url}
                      target="_blank"
                      rel="noopener noreferrer"
                      className={`social-link ${platform}`}
                    >
                      {platform}
                    </a>
                  ))}
                </div>
              )}
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default E2CPage;
```

**`src/components/E2C/E2CPage.css`**
```css
.e2c-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.e2c-header {
  text-align: center;
  margin-bottom: 50px;
}

.e2c-logo {
  max-width: 300px;
  margin-bottom: 20px;
}

.e2c-section {
  margin-bottom: 60px;
}

.e2c-section h2 {
  font-size: 2rem;
  margin-bottom: 30px;
  text-align: center;
}

.e2c-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
}

.e2c-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 20px;
  transition: transform 0.3s ease;
}

.e2c-card:hover {
  transform: translateY(-5px);
}

.e2c-card-image {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 15px;
}

.jury-card {
  border-top: 4px solid #FFD700;
}

.participant-card {
  border-top: 4px solid #FF6B35;
}

.social-links {
  display: flex;
  gap: 10px;
  margin-top: 15px;
}

.social-links a {
  padding: 8px 12px;
  background: #f0f0f0;
  border-radius: 5px;
  text-decoration: none;
  color: #333;
  font-size: 0.9rem;
}

.social-links a:hover {
  background: #e0e0e0;
}

.gallery {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
  margin-top: 15px;
}

.gallery-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 5px;
}

.e2c-loading, .e2c-error {
  text-align: center;
  padding: 50px;
  font-size: 1.2rem;
}

.spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #FF6B35;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
```

---

### 6. Détail d'un article E2C

**`src/components/E2C/E2CArticleDetail.jsx`**
```javascript
import React from 'react';
import { useParams } from 'react-router-dom';
import { useFetch } from '../hooks/useFetch';

const E2CArticleDetail = () => {
  const { salonId, articleId } = useParams();

  const { data: article, loading, error } = useFetch(
    `/salons/${salonId}/e2c/${articleId}`
  );

  if (loading) return <div>Chargement...</div>;
  if (error) return <div>Erreur : {error}</div>;

  return (
    <div className="e2c-detail">
      <img src={article.featured_image} alt={article.title} />
      <h1>{article.title}</h1>

      <div dangerouslySetInnerHTML={{ __html: article.content }} />

      {/* Galerie */}
      {article.gallery && article.gallery.length > 0 && (
        <div className="gallery">
          <h2>Galerie</h2>
          <div className="gallery-grid">
            {article.gallery.map((image, index) => (
              <img key={index} src={image} alt={`Image ${index + 1}`} />
            ))}
          </div>
        </div>
      )}

      {/* Vidéos */}
      {article.videos && article.videos.length > 0 && (
        <div className="videos">
          <h2>Vidéos</h2>
          {article.videos.map((video, index) => (
            <iframe
              key={index}
              src={video}
              title={`Video ${index + 1}`}
              frameBorder="0"
              allowFullScreen
            />
          ))}
        </div>
      )}

      {/* Réseaux sociaux */}
      {article.social_links && (
        <div className="social-links">
          <h2>Suivez-moi</h2>
          {Object.entries(article.social_links).map(([platform, url]) => (
            <a
              key={platform}
              href={url}
              target="_blank"
              rel="noopener noreferrer"
            >
              {platform}
            </a>
          ))}
        </div>
      )}
    </div>
  );
};

export default E2CArticleDetail;
```

---

### 7. Service API complet

**`src/services/tgsApi.js`**
```javascript
import api from './api';

export const TGSApi = {
  // Salons
  getSalons: () => api.get('/salons'),
  getSalon: (id) => api.get(`/salons/${id}`),
  getSalonStats: (id) => api.get(`/salons/${id}/stats`),

  // Articles
  getArticles: (salonId, params = {}) => {
    const queryString = new URLSearchParams(params).toString();
    return api.get(`/salons/${salonId}/articles?${queryString}`);
  },
  getArticle: (salonId, articleId) =>
    api.get(`/salons/${salonId}/articles/${articleId}`),
  getFeaturedArticles: (salonId) =>
    api.get(`/salons/${salonId}/articles/featured`),

  // Catégories
  getCategories: (salonId) => api.get(`/salons/${salonId}/categories`),
  getCategory: (salonId, categoryId) =>
    api.get(`/salons/${salonId}/categories/${categoryId}`),
  getCategoryArticles: (salonId, categoryId) =>
    api.get(`/salons/${salonId}/categories/${categoryId}/articles`),

  // E2C
  getE2C: (salonId, search = '') => {
    const params = search ? `?search=${encodeURIComponent(search)}` : '';
    return api.get(`/salons/${salonId}/e2c${params}`);
  },
  getE2CArticle: (salonId, articleId) =>
    api.get(`/salons/${salonId}/e2c/${articleId}`),

  // FAQs
  getFaqs: (salonId) => api.get(`/salons/${salonId}/faqs`),

  // Infos pratiques
  getPracticalInfos: (salonId) =>
    api.get(`/salons/${salonId}/practical-infos`),

  // Partenaires
  getPartners: (salonId) => api.get(`/salons/${salonId}/partners`),

  // Prix billets
  getTicketPrices: (salonId) => api.get(`/salons/${salonId}/ticket-prices`),

  // Pages spéciales
  getPresse: (salonId) => api.get(`/salons/${salonId}/presse`),
  getPhotosInvites: (salonId) =>
    api.get(`/salons/${salonId}/photos-invites`),
  getBecomeExhibitor: (salonId) =>
    api.get(`/salons/${salonId}/become-exhibitor`),
  getBecomeStaff: (salonId) => api.get(`/salons/${salonId}/become-staff`),

  // Tags
  getTags: () => api.get('/tags'),
  searchTags: (query) => api.get(`/tags/search?q=${encodeURIComponent(query)}`),

  // Contenus billetterie
  getTicketContents: () => api.get('/ticket-contents'),
  searchTicketContents: (query) =>
    api.get(`/ticket-contents/search?q=${encodeURIComponent(query)}`),
};

export default TGSApi;
```

---

### 8. Exemple d'utilisation avec React Query

```bash
npm install @tanstack/react-query
```

**`src/hooks/useE2C.js`**
```javascript
import { useQuery } from '@tanstack/react-query';
import TGSApi from '../services/tgsApi';

export const useE2C = (salonId, options = {}) => {
  return useQuery({
    queryKey: ['e2c', salonId],
    queryFn: () => TGSApi.getE2C(salonId),
    ...options,
  });
};

export const useE2CArticle = (salonId, articleId, options = {}) => {
  return useQuery({
    queryKey: ['e2c-article', salonId, articleId],
    queryFn: () => TGSApi.getE2CArticle(salonId, articleId),
    enabled: !!articleId,
    ...options,
  });
};
```

**Utilisation:**
```javascript
import { useE2C } from '../hooks/useE2C';

const E2CPage = ({ salonId }) => {
  const { data, isLoading, error } = useE2C(salonId);

  if (isLoading) return <div>Chargement...</div>;
  if (error) return <div>Erreur : {error.message}</div>;

  const { content, jury, participants } = data?.data || {};

  return (
    // ... JSX
  );
};
```

---

## 🎯 Bonnes pratiques

### 1. Gestion du cache
```javascript
// Utiliser React Query pour le cache automatique
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 5 * 60 * 1000, // 5 minutes
      cacheTime: 10 * 60 * 1000, // 10 minutes
    },
  },
});

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      {/* Votre app */}
    </QueryClientProvider>
  );
}
```

### 2. Gestion des erreurs
```javascript
// Créer un composant ErrorBoundary
class ErrorBoundary extends React.Component {
  state = { hasError: false };

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  render() {
    if (this.state.hasError) {
      return <div>Une erreur est survenue</div>;
    }
    return this.props.children;
  }
}
```

### 3. Optimisation des performances
```javascript
// Utiliser React.memo pour éviter les re-renders inutiles
const ArticleCard = React.memo(({ article }) => {
  return (
    <div className="article-card">
      {/* ... */}
    </div>
  );
});

// Lazy loading des images
<img
  src={article.featured_image}
  alt={article.title}
  loading="lazy"
/>
```

### 4. Environnement de développement vs production
```javascript
// .env.development
REACT_APP_API_URL=http://127.0.0.1:8000/api/api/v1

// .env.production
REACT_APP_API_URL=https://votre-domaine.com/api/api/v1

// api.js
const API_BASE_URL = process.env.REACT_APP_API_URL;
```

### 5. Pagination
```javascript
const ArticleList = ({ salonId }) => {
  const [page, setPage] = useState(1);
  const { data } = useFetch(`/salons/${salonId}/articles?page=${page}`);

  return (
    <>
      {/* Articles */}
      <button onClick={() => setPage(p => p - 1)} disabled={page === 1}>
        Précédent
      </button>
      <button onClick={() => setPage(p => p + 1)}>
        Suivant
      </button>
    </>
  );
};
```

---

## 📞 Support

Pour toute question ou problème :
- **Email:** support@tgs.com
- **Documentation:** https://docs.tgs.com
- **Issues:** https://github.com/tgs/issues

---

## 📝 Changelog

### Version 1.0.0 (2025-10-12)
- ✅ Refactorisation de l'API E2C (5 endpoints → 2)
- ✅ Endpoint `index` pour récupérer tout E2C en un seul call
- ✅ Documentation complète avec exemples React
- ✅ Ajout du seeder complet pour toutes les données

---

**© 2025 Toulouse Game Show - Tous droits réservés**
