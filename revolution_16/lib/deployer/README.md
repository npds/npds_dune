# 🚀 NPDS Deployer - Déploiement Automatique

## 📥 Téléchargement et Installation

### Étape 1 : Téléchargez le déployeur
**⚠️ IMPORTANT :** Téléchargez le fichier **à la RACINE de votre domaine/sous-domaine** :

#### Option 1 : Lien de téléchargement automatique :
[📦 Télécharger npds_deployer.php](https://github.com/npds/npds_dune/raw/master/revolution_16/lib/deployer/npds_deployer.php)

#### Option 2 : Commandes terminal :

```bash
# Via wget (recommandé)
wget -O npds_deployer.php https://raw.githubusercontent.com/npds/npds_dune/master/revolution_16/lib/deployer/npds_deployer.php

# Ou via curl
curl -o npds_deployer.php https://raw.githubusercontent.com/npds/npds_dune/master/revolution_16/lib/deployer/npds_deployer.php
```

#### Option 3 : Copier-coller manuel :

1. Cliquez droit sur ce [lien](https://github.com/npds/npds_dune/raw/master/revolution_16/lib/deployer/npds_deployer.php)
2. "Enregistrer le lien sous..."
3. Nommez le fichier npds_deployer.php

### Étape 2 : Placez-le au bon endroit
**📍 EMPLACEMENT CORRECT :**
```
https://votre-domaine.com/npds_deployer.php  ✅
```

**❌ EMPLACEMENTS INCORRECTS :**
```
https://votre-domaine.com/dossier/npds_deployer.php  ❌
https://votre-domaine.com/npds/npds_deployer.php     ❌
```

### Étape 3 : Lancez le déploiement
Accédez à l'URL dans votre navigateur :
```
https://votre-domaine.com/npds_deployer.php
```

## 🎯 Pourquoi à la racine ?

Le déployeur doit être à la racine car :
1. **Déploiement flexible** : Peut installer NPDS à la racine ou dans un sous-dossier
2. **Sécurité** : S'auto-détruit après l'installation complète
3. **Simplicité** : Accès direct sans chemins complexes

## 🔧 Utilisation Avancée

### Déploiement dans un sous-dossier
```
https://votre-domaine.com/npds_deployer.php?op=deploy&version=v.16.4&path=npds&confirm=yes
```

### Déploiement à la racine  
```
https://votre-domaine.com/npds_deployer.php?op=deploy&version=v.16.4&confirm=yes
```

## 🔒 Sécurité

- Le déployeur sera **auto-détruit** après l'installation réussie
- Une copie de sécurité sera conservée dans `votre-site.com/npds/lib/deployer/npds_deployer.php` pour usage futur
- **Ne renommez pas** le fichier - gardez `npds_deployer.php`
- **Ne fonctionne que pour les nouvelles installations** (bloqué si `IZ-Xinstall.ok` existe)

---

**💡 Conseil :** Laissez le déployeur à la racine - il sera supprimé automatiquement une fois NPDS installé !