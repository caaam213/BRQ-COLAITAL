console.log("hello");

// Fonctions de calculs

function updatePU()
{
  /*
    Mettre à jour le prix unitaire d'un produit en fonction de la quantité et de la valeur
  */

  // On récupère l'id du produit
  if( this.id.includes('qteProduit'))
  {
    var idProduit = this.id.replace('qteProduit', '');
  }
  else
  {
    var idProduit = this.id.replace('valeurProduit', '');
  }

  // A chaque changement des valeurs de la quantité ou des valeurs, on met à jour le prix unitaire
  var qte = document.getElementById('qteProduit'+idProduit)=== null ? 0 : document.getElementById('qteProduit'+idProduit).value;
  var valeur = document.getElementById('valeurProduit'+idProduit) === null ? 0 : document.getElementById('valeurProduit'+idProduit).value;

  if(qte == 0 && valeur == 0)
  {
      document.getElementById('PUProduit'+idProduit).innerHTML = '';
  }
  else
  {
    // On convertit les valeurs en entier
      qte = parseInt(qte);
      valeur = parseInt(valeur);
      var PU = valeur / qte;

      // On vérifie si PU est négatif ou s'il y a une division par 0
      if(PU < 0 || isNaN(PU))
      {
        document.getElementById('PUProduit'+idProduit).innerHTML = 'ERREUR';
      }
      else if(PU == Infinity)
      {
      document.getElementById('PUProduit'+idProduit).innerHTML = 'DIV/0';
      }
      else
      {
      document.getElementById('PUProduit'+idProduit).innerHTML = PU.toFixed(2);
      }
  }
}


function updateValues(className, parseMethod, idName)
{
  /*
    Fonction utilitaire pour mettre à jour les valeurs des champs qui nécessite un calcul
  */

  var elements = document.getElementsByClassName(className);
  var total = 0;
  for (var i = 0; i < elements.length; i++) {
    var element = elements[i].value == '' ? 0 : elements[i].value;

    if(parseMethod == 'parseInt')
    {
      total += isNaN(parseInt(element)) ? 0 : parseInt(element);
    }
    else if(parseMethod == 'parseFloat')
    {
      total += isNaN(parseFloat(element)) ? 0 : parseFloat(element);
    }
    else
    {
      total += element;
    }
  }
  
  document.getElementById(idName).innerHTML = total;
}


function getAllIdCategories()
{
  /*
    Récupérer tous les id des catégories
  */
  var categories = document.getElementsByClassName('categories');
  var categoriesId = [];
  for (var i = 0; i < categories.length; i++) {
    var categorie = categories[i].id.replace('rowCategorie', '');
    categoriesId.push(categorie);
  };
  return categoriesId;
}


function updateAllStockCat()
{
  /*
    Mettre à jour les stocks finaux des catégories en ajoutant les stocks de chaque produit liés
  */ 
  var categoriesId = getAllIdCategories();
  for (var i = 0; i < categoriesId.length; i++) {
    var stocksFinCat = document.getElementsByClassName('stocksFinsCat'+categoriesId[i]);
    var stockFinal = 0;
    for (var j = 0; j < stocksFinCat.length; j++) {
      stockFinal += parseInt(stocksFinCat[j].innerHTML) || 0;
    }

    document.getElementById('stockFinCat'+categoriesId[i]).innerHTML = stockFinal;
  }
}


function updateAllStockProduit(className, idToUpdateName)
{
  /*
    Mettre à jour les stocks finaux des produits laitiers et total
  */ 
  var stocksFinProduit = document.getElementsByClassName(className);
  var stockFinal = 0;
  for (var i = 0; i < stocksFinProduit.length; i++) {
    stockFinal += parseInt(stocksFinProduit[i].innerHTML) || 0;
  }

  document.getElementById(idToUpdateName).innerHTML = stockFinal;
}













// Ajouter un évènements aux inputs de réalisation afin de les comparer aux objectifs
var realisations = document.getElementsByClassName('realisations');
for (var i = 0; i < realisations.length; i++) {
    realisations[i].addEventListener('keyup', function() {
    var idProduit = this.id.replace('realisationProduit', '');
    var objectif = document.getElementById('rowProduct'+idProduit).getElementsByClassName('objectifs')[0].innerHTML;
    var realisation = this.value;

    // On convertit les valeurs en entier
    objectif = parseInt(objectif);
    realisation = parseInt(realisation);

    if(isNaN(realisation))
    {
        document.getElementById('realisationProduit'+idProduit).style.backgroundColor = '#ffffff';
    }
    else if (realisation < objectif) {   
        // On colore en rouge la ligne du produit
        document.getElementById('realisationProduit'+idProduit).style.backgroundColor = '#ffcccc';
    }
    else
    {
        // On colore en vert la ligne du produit
        document.getElementById('realisationProduit'+idProduit).style.backgroundColor = '#ccffcc';
    }
});
}



// On ajoute l'évènement aux inputs
var quantites = document.getElementsByClassName('quantitesInput');
var valeurs = document.getElementsByClassName('valeursInput');
for (var i = 0; i < quantites.length; i++) {
    quantites[i].addEventListener('keyup', updatePU);
    valeurs[i].addEventListener('keyup', updatePU);
}


  // On met à jour les valeurs des produits laitiers en ajoutant les objectifs ayant la classe "objectifsProduitLaitier"
  var objectifsProduitLaitier = document.getElementsByClassName('objectifsProduitLaitier');
  var produitsLaitiersObjectif = 0;
  for (var i = 0; i < objectifsProduitLaitier.length; i++) {
    produitsLaitiersObjectif += parseInt(objectifsProduitLaitier[i].innerHTML);
  }
  document.getElementById('produitsLaitiersObjectif').innerHTML = produitsLaitiersObjectif;

  // On met à jour les valeurs des objectifs totaux
  var objectifs = document.getElementsByClassName('objectifs');
  var objectifsTotal = 0;
  for (var i = 0; i < objectifs.length; i++) {
    objectifsTotal += parseInt(objectifs[i].innerHTML);
  }
  document.getElementById('totalToutFaitObjectif').innerHTML = objectifsTotal;

  


  // A chaque fois qu'on change les valeurs des réalisations, on recalcule la réalisation totale des produits laitiers 
  var realisationsProduitLaitier = document.getElementsByClassName('realisationsProduitLaitier');
  // Evènement sur les inputs de réalisation
  for (var i = 0; i < realisationsProduitLaitier.length; i++) {
    realisationsProduitLaitier[i].addEventListener('keyup',function()
    {
      updateValues('realisationsProduitLaitier', 'parseInt', 'produitsLaitiersRealisation');
    });
  };
  

  // A chaque fois qu'on change les valeurs des quantités, on recalcule la quantité totale des produits laitiers
  var quantitesProduitLaitier = document.getElementsByClassName('qtesProduitLaitier');

  // Evènement sur les inputs de quantité
  for (var i = 0; i < quantitesProduitLaitier.length; i++) {
      quantitesProduitLaitier[i].addEventListener('keyup', function() {
        updateValues('qtesProduitLaitier', 'parseInt', 'produitsLaitiersQte');
      });
  };
  

  // A chaque fois qu'on change les valeurs des valeurs, on recalcule la valeur totale des produits laitiers
  var valeursProduitLaitier = document.getElementsByClassName('valeursProduitLaitier');
  // Evènement sur les inputs de valeur
  for (var i = 0; i < valeursProduitLaitier.length; i++) {
    valeursProduitLaitier[i].addEventListener('keyup', function()
    {
      updateValues('valeursProduitLaitier', 'parseFloat', 'produitsLaitiersValeur');
    });
  };

  // A chaque fois qu'on change les valeurs des stocks, on recalcule le stock total des produits laitiers
  var stocksDebutProduitLaitier = document.getElementsByClassName('stocksDebutsProduitLaitier');
  // Evènement sur les inputs de stock
  for (var i = 0; i < stocksDebutProduitLaitier.length; i++) {
    stocksDebutProduitLaitier[i].addEventListener('keyup', function()
    {
      updateValues('stocksDebutsProduitLaitier', 'parseInt', 'produitsLaitiersStockDebut');
    });
  };

  // A chaque fois qu'on change les valeurs des stocks, on recalcule le stock total des produits laitiers
  var stocksFinProduitLaitier = document.getElementsByClassName('stocksFinsProduitLaitier');
  // Evènement sur les inputs de stock
  for (var i = 0; i < stocksFinProduitLaitier.length; i++) {
    stocksFinProduitLaitier[i].addEventListener('keyup', function()
    {
      updateValues('stocksFinsProduitLaitier', 'parseInt', 'produitsLaitiersStockFin');
    });
  };


  // A chaque fois qu'on change les valeurs des réalisations, on recalcule la réalisation totale
  var realisations = document.getElementsByClassName('realisations');
  // Evènement sur les inputs de réalisation
  for (var i = 0; i < realisations.length; i++) {
    realisations[i].addEventListener('keyup',function()
    {
      updateValues('realisations', 'parseInt', 'totalToutFaitRealisation');
    });
  };

  // A chaque fois qu'on change les valeurs des quantités, on recalcule la quantité totale
  var quantites = document.getElementsByClassName('quantites');
  // Evènement sur les inputs de quantité
  for (var i = 0; i < quantites.length; i++) {
      quantites[i].addEventListener('keyup', function() {
        updateValues('quantites', 'parseInt', 'totalToutFaitQte');
      });
  };

  // A chaque fois qu'on change les valeurs des valeurs, on recalcule la valeur totale
  var valeurs = document.getElementsByClassName('valeurs');
  // Evènement sur les inputs de valeur
  for (var i = 0; i < valeurs.length; i++) {
    valeurs[i].addEventListener('keyup', function()
    {
      updateValues('valeurs', 'parseFloat', 'totalToutFaitValeur');
    });
  };

  // A chaque fois qu'on change les valeurs des stocks de début de journée, on recalcule le stock de début de journée total
  var stocksDebut = document.getElementsByClassName('stocksDebuts');
  // Evènement sur les inputs de stock de début de journée
  for (var i = 0; i < stocksDebut.length; i++) {
    stocksDebut[i].addEventListener('keyup',function()
    {
      updateValues('stocksDebuts', 'parseInt', 'totalToutFaitStockDebut');
    });
  };

  // A chaque fois qu'on change les valeurs des stocks de fin de journée, on recalcule le stock de fin de journée total
  var stocksFin = document.getElementsByClassName('stocksFins');
  // Evènement sur les inputs de stock de fin de journée
  for (var i = 0; i < stocksFin.length; i++) {
    stocksFin[i].addEventListener('keyup',function()
    {
      updateValues('stocksFins', 'parseInt', 'totalToutFaitStockFin');
    });
  };

  

  var categoriesId = getAllIdCategories();

  
  // On met à jour les valeurs des categories
  for (var i = 0; i < categoriesId.length; i++) {
    var realisationsProduit = document.getElementsByClassName('realisationsCat'+categoriesId[i]);
    var qtes = document.getElementsByClassName('qtesCat'+categoriesId[i]);
    var valeurs = document.getElementsByClassName('valeursCat'+categoriesId[i]);
    var stockDebutJournees = document.getElementsByClassName('stocksDebutsCat'+categoriesId[i]);
    var stockFinJournees = document.getElementsByClassName('stocksFinsCat'+categoriesId[i]);

    for (var j = 0; j < realisationsProduit.length; j++) {
        realisationsProduit[j].addEventListener('keyup', ((categoryId) => {
            return () => {
                updateValues('realisationsCat'+categoryId, 'parseInt', 'realisationCat'+categoryId);
            };
        })(categoriesId[i]));

        qtes[j].addEventListener('keyup', ((categoryId) => {
            return () => {
                updateValues('qtesCat'+categoryId, 'parseInt', 'qteCat'+categoryId);
            };
        })(categoriesId[i]));

        valeurs[j].addEventListener('keyup', ((categoryId) => {
            return () => {
                updateValues('valeursCat'+categoryId, 'parseFloat', 'valeurCat'+categoryId);
            };
        })(categoriesId[i]));

        stockDebutJournees[j].addEventListener('keyup', ((categoryId) => {
            return () => {
                updateValues('stocksDebutsCat'+categoryId, 'parseInt', 'stockDebutCat'+categoryId);
            };
        })(categoriesId[i]));

        // Mettre à jour les stocks finaux
        stockFinJournees[j].addEventListener('change', ((categoryId) => {
            return () => {
                updateValues('stocksFinsCat'+categoryId, 'parseInt', 'stockFinCat'+categoryId);
            };
        })(categoriesId[i]));
    }
  }

  // Calcul des stocks finaux à chaque changement :
  // Stock début de journée +réalisation - quantité
  function updateStockFin()
  {
    
    if( this.id.includes('qteProduit'))
    {
      var idProduit = this.id.replace('qteProduit', '');
    }
    else if(this.id.includes('realisationProduit'))
    {
      var idProduit = this.id.replace('realisationProduit', '');
    }
    else if(this.id.includes('stocksDebutProduit'))
    {
      var idProduit = this.id.replace('stocksDebutProduit', '');
    }

    var stockDebut = document.getElementById('stocksDebutProduit'+idProduit).value=='' ? 0 : document.getElementById('stocksDebutProduit'+idProduit).value;
    var realisation = document.getElementById('realisationProduit'+idProduit).value=='' ? 0 : document.getElementById('realisationProduit'+idProduit).value;
    var quantite = document.getElementById('qteProduit'+idProduit).value=='' ? 0 : document.getElementById('qteProduit'+idProduit).value;

    var stockFinal = parseInt(stockDebut) + parseInt(realisation) - parseInt(quantite);
    if (isNaN(stockFinal)) {
      stockFinal = 0;
    }

    if (stockFinal<0)
    {
      stockFinal = 'ERREUR';
    }

    document.getElementById('stockFinProduit'+idProduit).innerHTML = stockFinal;

  }

  // Ajout d'évènements à chaque fois qu'on modifie ces valeurs
  var qtes = document.getElementsByClassName('quantitesInput');
  var realisations = document.getElementsByClassName('realisations');
  var stocksDebut = document.getElementsByClassName('stocksDebuts');

  for (var i = 0; i < qtes.length; i++) {
    qtes[i].addEventListener('keyup', updateStockFin);
    realisations[i].addEventListener('keyup', updateStockFin);
    stocksDebut[i].addEventListener('keyup', updateStockFin);

    qtes[i].addEventListener('keyup', updateAllStockCat);
    realisations[i].addEventListener('keyup', updateAllStockCat);
    stocksDebut[i].addEventListener('keyup', updateAllStockCat);

    // Produit laitiers
    qtes[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    realisations[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    stocksDebut[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFinsProduitLaitier', 'produitsLaitiersStockFin'));

    // Totaux
    qtes[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    realisations[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));

    stocksDebut[i].addEventListener('keyup', ((className, idToUpdateName) => {
        return () => {
          updateAllStockProduit(className, idToUpdateName);
        };
    })('stocksFins', 'totalToutFaitStockFin'));


  
  };

  