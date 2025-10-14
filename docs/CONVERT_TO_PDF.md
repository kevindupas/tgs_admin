# 📄 Guide de Conversion en PDF

Plusieurs méthodes pour convertir la documentation en PDF.

---

## ✅ Méthode 1 : Via HTML + Navigateur (Recommandé - Sans installation)

### Étape 1 : Le fichier HTML est déjà créé
```
docs/API_TGS.html
```

### Étape 2 : Ouvrir dans le navigateur
```bash
# Sur macOS
open docs/API_TGS.html

# Sur Linux
xdg-open docs/API_TGS.html

# Ou double-cliquer sur le fichier
```

### Étape 3 : Imprimer en PDF
1. Dans le navigateur : **Cmd+P** (ou Ctrl+P)
2. Choisir **"Enregistrer au format PDF"** comme destination
3. Cliquer sur **"Enregistrer"**
4. Le PDF est prêt ! 🎉

**Avantages :**
- ✅ Aucune installation requise
- ✅ Mise en page propre avec GitHub Markdown CSS
- ✅ Coloration syntaxique du code
- ✅ Fonctionne sur tous les OS

---

## ✅ Méthode 2 : Avec wkhtmltopdf (Meilleur rendu)

### Installation
```bash
# Sur macOS
brew install wkhtmltopdf

# Sur Ubuntu/Debian
sudo apt-get install wkhtmltopdf

# Sur Windows
# Télécharger depuis : https://wkhtmltopdf.org/downloads.html
```

### Génération du PDF
```bash
pandoc docs/API_DOCUMENTATION.md -o docs/API_TGS.pdf --pdf-engine=wkhtmltopdf
```

**Avantages :**
- ✅ Automatique via ligne de commande
- ✅ Bon rendu du Markdown
- ✅ Gestion des sauts de page

---

## ✅ Méthode 3 : Avec VSCode (Facile)

### Installation
1. Ouvrir VSCode
2. Aller dans Extensions (Cmd+Shift+X)
3. Chercher **"Markdown PDF"**
4. Installer l'extension

### Génération du PDF
1. Ouvrir `docs/API_DOCUMENTATION.md` dans VSCode
2. **Cmd+Shift+P** (ou Ctrl+Shift+P)
3. Taper : `Markdown PDF: Export (pdf)`
4. Le PDF est créé dans le même dossier !

**Avantages :**
- ✅ Interface graphique
- ✅ Aperçu avant export
- ✅ Configuration personnalisable

---

## ✅ Méthode 4 : En ligne (Rapide)

### Sites recommandés

**1. Markdown to PDF**
- URL : https://www.markdowntopdf.com/
- Étapes :
  1. Copier le contenu de `API_DOCUMENTATION.md`
  2. Coller dans le site
  3. Cliquer sur "Convert"
  4. Télécharger le PDF

**2. Dillinger**
- URL : https://dillinger.io/
- Étapes :
  1. Ouvrir le site
  2. Copier/coller le Markdown
  3. Export → PDF

**Avantages :**
- ✅ Aucune installation
- ✅ Rapide (2 minutes)
- ✅ Fonctionne partout

---

## 🎨 Personnaliser le PDF

### Avec CSS personnalisé

Si vous voulez modifier le style du HTML avant conversion :

```bash
# Créer un fichier CSS personnalisé
cat > docs/custom-style.css << 'EOF'
body {
  font-family: 'Helvetica', Arial, sans-serif;
  max-width: 900px;
  margin: 40px auto;
  padding: 20px;
}

h1 { color: #FF6B35; }
h2 { color: #333; border-bottom: 2px solid #FF6B35; }
code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }
pre { background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 5px; }
EOF

# Générer le HTML avec ce style
pandoc docs/API_DOCUMENTATION.md -o docs/API_TGS_Styled.html --standalone --css docs/custom-style.css

# Puis ouvrir et imprimer en PDF
open docs/API_TGS_Styled.html
```

---

## 📊 Comparaison des Méthodes

| Méthode | Facilité | Qualité | Installation |
|---------|----------|---------|--------------|
| HTML + Navigateur | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | Aucune |
| wkhtmltopdf | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ | Requise |
| VSCode Extension | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | Extension |
| En ligne | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | Aucune |

---

## 🚀 Méthode Recommandée pour Vous

**Utilisez la Méthode 1 (HTML + Navigateur) :**

1. Le fichier `docs/API_TGS.html` est déjà créé
2. Double-cliquez dessus (ou `open docs/API_TGS.html`)
3. Cmd+P → "Enregistrer en PDF"
4. Voilà ! 🎉

C'est **le plus simple** et le rendu est **très bon**.

---

## 💡 Astuce : Améliorer le Rendu PDF

Avant d'imprimer en PDF depuis le navigateur :

1. **Afficher le HTML** dans Chrome/Edge
2. **F12** → Console
3. Coller ce code pour améliorer l'apparence :

```javascript
// Ajuster les marges
document.body.style.maxWidth = '800px';
document.body.style.margin = '40px auto';

// Améliorer la coloration des blocs de code
document.querySelectorAll('pre code').forEach(block => {
  block.style.fontSize = '12px';
  block.style.lineHeight = '1.5';
});
```

4. Puis **Cmd+P** pour imprimer en PDF

---

## ✅ Fichiers Disponibles

Après conversion, vous aurez :

```
docs/
├── API_DOCUMENTATION.md        # Source Markdown
├── API_TGS.html                # Version HTML (déjà créé ✅)
└── API_TGS.pdf                 # Version PDF (à créer)
```

---

## 📞 En Cas de Problème

**Le PDF est mal formaté ?**
→ Essayez la méthode wkhtmltopdf ou VSCode

**Le code n'est pas coloré ?**
→ Utilisez le HTML avec le navigateur Chrome

**Les images ne s'affichent pas ?**
→ Vérifiez que les URLs des images sont accessibles

**Besoin d'un style spécifique ?**
→ Personnalisez le CSS (voir section ci-dessus)

---

**🎯 Pour gagner du temps : Utilisez la Méthode 1 (HTML → PDF via navigateur)**

C'est déjà fait, il suffit d'ouvrir `docs/API_TGS.html` et d'imprimer en PDF !
