# CarbonScale
Bonjour, voici le prototype d'application Carbon Scale.

Ce GitHub correspond a l'API sous Symfony 6, crée dans le cadre du Hack2Horizon. 

Cette API est faire pour régérer les actions des utilisateurs. 

Voici la désciption de diférents EndPoint : 


#API/Categories 
Permet de récuperer toutes les catégorie mise dans la base de donnée

#API/{Nom de la categorie}/Ressource
Permet de récuperer toutes les ressources, lié a la catégorie spécifiée dans {Nom de la categorie}

#API/Action/Last
Récupère les 4 denière action de l'utilisateur

#API/Action 
Permet d'ajouter une action, lié a un utilisateur. 

A mon avis, il est préférable de re créer une api a zero, pour mettre les vraie donnée. utilise cette API comme un exemple, plutot que de l'utiliser :) 
