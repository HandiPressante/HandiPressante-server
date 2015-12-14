SET foreign_key_checks = 0;

DROP TABLE IF EXISTS toilettes;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS notes;
DROP VIEW IF EXISTS fiches;

SET foreign_key_checks = 1;


CREATE TABLE users(
id_users varchar(50) PRIMARY KEY
);

CREATE TABLE notes(
id_users_notes varchar(50) references users(id_users),
id_toilettes_notes INT references toilettes(id_toilettes),
proprete INT,
equipement INT,
accessibilite INT
);


CREATE TABLE images(
	id_images INT references toilettes(id)
  ,image varchar (100) PRIMARY KEY 
);


CREATE TABLE toilettes(
	id_toilettes INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
	,description TEXT
  ,nom VARCHAR(40)
  ,lieu VARCHAR(60)
  ,horaires VARCHAR(100)
  ,pmr BOOLEAN
  ,x93 double 
  ,y93 double
   ,x84 double 
  ,y84 double
);

CREATE INDEX index_x84 ON toilettes (x84);
CREATE INDEX index_y84 ON toilettes (y84);
CREATE INDEX index_x93 ON toilettes (x93);
CREATE INDEX index_y93 ON toilettes (y93);


INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'JARDINS FAMILIAUX BINTINAIS','Départemental 82','de Mars à Septembre',NULL,'352914.52','6785627.76','-1.66353078','48.07875497');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MAIRIE OPERA','Place de la Mairie','du Lundi au Samedi 8h à 19h40.',FALSE,'351996.02','6789296.02','-1.67876029','48.11121760');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'HALLES CENTRALES','2 place Honoré Commeurec','de 7h à 19h sauf le dimanche',FALSE,'351904.30','6788944.86','-1.67971106','48.10801440');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'CHAMPS LIBRES','Cours des Alliers','du M au V de 12h à 19h, sauf mardi  21h; les S et D 14h à 19h.',TRUE,'352291.20','6788626.93','-1.67426905','48.10536455');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'FREVILLE','Place de la Communauté',NULL,TRUE,'352149.20','6787240.74','-1.67507159','48.09283712');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GARE ROUTIERE','Bd Solférino',NULL,NULL,'352511.39','6788449.03','-1.67117467','48.10388373');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'KLEBER_2','1 rue Kléber','Horaires du parking',FALSE,'352364.81','6789220.63','-1.67375367','48.11073684');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GARE','Place de la Gare','6h à 23h',TRUE,'352370.34','6788501.64','-1.67310805','48.10428124');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'KLEBER parking','1 rue Kléber','Horaires du parking',FALSE,'352377.15','6789221.47','-1.67358888','48.11075092');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','Parc des Gayeuilles SUD "Tatelin"',NULL,NULL,'354845.89','6791398.35','-1.64219330','48.13161492');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','Parc des Gayeuilles SUD "patinoire"',NULL,NULL,'354510.78','6791373.25','-1.64667021','48.13121225');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GUY HOUIST','Sq. Guy Houist',NULL,FALSE,'351168.24','6789553.46','-1.69006865','48.11308845');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'SAINT-CYR','61 quai St. Cyr','6h à 23h',TRUE,'350887.77','6788986.64','-1.69337824','48.10784704');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MOULIN du COMTE','1 Sq. Simone Morand','6h à 23h',TRUE,'349827.22','6789164.42','-1.70774463','48.10887626');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','rue Maurice AUDIN',NULL,NULL,'354609.75','6792070.14','-1.64589295','48.13752460');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'CIMETIERE NORD','35 Rue Victor Ségalen','Horaires du Cimetière',FALSE,'351864.41','6790818.39','-1.68173770','48.12482229');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Saint MARTIN Ecluse','10 Canal St. Martin parking','6h à 23h',TRUE,'351867.31','6790389.04','-1.68135683','48.12096715');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'PARC des TANNEURS','6 rue St Martin',NULL,NULL,'352051.99','6790214.13','-1.67874009','48.11949446');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'PREFECTURE','3 av de la Préfecture','Horaires Préfecture',TRUE,'350969.80','6791164.58','-1.69401658','48.12745435');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','40 Ave des Gayeulles',NULL,TRUE,'354155.23','6791883.31','-1.65184486','48.13560571');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','Chemin Mazé',NULL,NULL,'355213.45','6792303.83','-1.63797564','48.13994294');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PARC','Jardins familliaux des Gayeulles',NULL,NULL,'355351.77','6792015.10','-1.63589153','48.13742233');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'COURTELINE','Rue Courteline',NULL,NULL,'353819.64','6790645.60','-1.65536793','48.12430978');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MAIL MITTERRAND','Mail Francois Mitterrand',NULL,NULL,'351054.89','6789123.38','-1.69124595','48.10916464');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MAUREPAS Parc','Face 16 bd. Raymond Poincaré','Horaires du Parc',TRUE,'353882.58','6790400.23','-1.65432920','48.12213905');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Cimetière Saint LAURENT','Face 29 rue de St. Laurent','Horaires du Cimetière',TRUE,'354012.26','6792202.54','-1.65401622','48.13839749');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'CIMETIERE EST','85 Bd. Villebois Mareuil','Horaires du Cimetière',TRUE,'354163.77','6788294.57','-1.64889163','48.10337328');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'SOUVENIR','Place du Souvenir Face 13 E. d''Orves','6h à 23h',TRUE,'353056.89','6787348.46','-1.66298604','48.09428751');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Z.A.C. St.HELIER','Adolphe Leray',NULL,TRUE,'353198.48','6788239.31','-1.66179353','48.10236504');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'SAINTE THERESE EGLISE','Place Hyacinte Perrin Eglise','6h à 23h',TRUE,'352753.63','6787445.92','-1.66712977','48.09500184');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'VILLENEUVE','Square de Villeneuve 1 r M. Berthelot','Horaires du Parc',TRUE,'351582.55','6787822.53','-1.68313257','48.09776128');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'PATTON','Bd. Armorique',NULL,NULL,'353025.40','6791366.36','-1.66659599','48.13036253');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'METRO CLEMENCEAU','1 Ave Fréville','6h à 23h',TRUE,'352241.42','6787310.16','-1.67389022','48.09350985');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BIR-HAKEIM','2 place Bir-Hakeim','6h à 23h',TRUE,'352740.02','6787188.31','-1.66710790','48.09268049');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GARE Intérieur','Esplanade Fulgence Bienvenue','du L au S 6h25 à 22h50 et le Dimanche 10h à 21h50',TRUE,'352388.29','6788313.98','-1.67271832','48.10260511');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'POLYCLINIQUE LA SAGESSE','4 place Guénolé','Heures de la clinique',TRUE,'349379.25','6788271.67','-1.71303732','48.10061676');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Jean GUY','12 rue Jean Guy','Horaires du parc',TRUE,'350717.85','6788880.17','-1.69557215','48.10679982');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Marché CLEUNAY','Allée Joseph Germains','6h à 23h',TRUE,'349927.50','6788235.74','-1.70565648','48.10058805');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ABELARD','Parking 1 rue Pierre Abélard','Horaires du parking',FALSE,'351365.65','6788631.33','-1.68668558','48.10491070');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'HOPITAL PONCHAILLOU','rue Henri Le Guilloux','24H / 24H',TRUE,'350919.19','6790207.77','-1.69393155','48.11883271');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Dalle Kennedy','à l''intérieur du bâtiment',NULL,NULL,'349595.82','6790599.84','-1.71199844','48.12164564');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'VILLEJEAN PARC','PARC de Villejean','TLJ',TRUE,'348905.59','6790733.98','-1.72136585','48.12247977');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'COURROUZE','La Courrouze',NULL,NULL,'350490.19','6788183.56','-1.69806902','48.10042070');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'HAUTES OURMES PARC','Parc des Hautes Ourmes','Horaires du Parc',NULL,'354356.17','6786731.42','-1.64507648','48.08943332');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Bibliotheque Villejean','à l''intérieur bibliothèque',NULL,NULL,'349673.66','6790538.60','-1.71090511','48.12113730');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'La TOUCHE Parc','Côté rue Maréchal Lyautey','Horaires du Parc',TRUE,'350966.21','6789940.71','-1.69308767','48.11645904');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'VILLEJEAN PISCINE','1 Sq.d''Alsace','Horaires piscine',TRUE,'350160.97','6790641.53','-1.70445001','48.12232321');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'VILLEJEAN BOURGOGNE','Rue de Bourgogne','6h à 23h',TRUE,'349922.93','6790322.92','-1.70738836','48.11933368');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BERRY','Parc du Berry','6h à 23h',TRUE,'349770.36','6790244.93','-1.70937252','48.11855138');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Eugene AULNETTE','Place Eugène Aulnette','6h à 23h',NULL,'350670.07','6791448.50','-1.69826492','48.12984436');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'VILLEJEAN KENNEDY','Dalle piétons Kennedy','6h à 23h',TRUE,'349679.88','6790588.56','-1.71086171','48.12158947');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BEAUREGARD PARC','Mail Emmanuel Le Ray','6h à 23h',TRUE,'350344.88','6791672.64','-1.70280758','48.13168352');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'FERME du LANDRY','198 Rue de Châteaugiron le Parc','TLJ',TRUE,'354582.84','6787481.26','-1.64262910','48.09628909');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'POTERIE','22 Place du Ronceray','6h à 23h',TRUE,'354656.51','6786886.59','-1.64117204','48.09098603');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ALEXIS CARELL marché','Face 65 Bd. Alexis Carrel','6h à 23h',NULL,'353844.04','6790203.07','-1.65469009','48.12034765');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'PATIS TATELIN','Rue du Patis Tatelin',NULL,NULL,'355114.32','6791789.50','-1.63890001','48.13527046');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Jeanne d''ARC','5 Bd. Alexis Carrel Eglise','6h à 23h',TRUE,'353693.52','6789793.56','-1.65638519','48.11658929');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLE','Rue du Professeur Maurice','TLJ',NULL,'354590.22','6791808.61','-1.64594840','48.13516499');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEUILLES Resto','Parc des Gayeulles','Horaires du Parc',TRUE,'354848.48','6791560.99','-1.64228711','48.13307728');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BERNANOS','Place G. Bernanos sud','6h à 23h',TRUE,'353474.58','6790581.40','-1.65994658','48.12355006');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PISCINE','16 Ave des Gayeulles','Horaires piscine',NULL,'354231.14','6791772.07','-1.65073806','48.13464665');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'GAYEULLES PATINOIRE','8 Ave des Gayeulles','TLJ vacances scolaires, et fermée les L, Ma, J, V, matin',NULL,'354179.27','6791542.70','-1.65125262','48.13255877');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ERLANGEN','Place Erlangen sud','6h à 23h',TRUE,'353532.26','6791325.99','-1.65976288','48.13026915');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ROBIDOU','Face 28 rue Bertrand Robidou','6h à 23h',TRUE,'353448.70','6788828.34','-1.65890440','48.10778901');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Saint-GEORGES','Jardin St. Georges',NULL,NULL,'352302.05','6789321.85','-1.67467602','48.11161261');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Haut des LICES 1 & 2','Place du Haut des LICES','n1 Jours de marchés & n2 TLJ',NULL,'351711.66','6789535.52','-1.68276510','48.11321736');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MAIRIE centrale','Place de la Mairie','du L au V 8h30 à 17h, le S de 9h30 à 12h',FALSE,'351910.90','6789344.98','-1.67994098','48.11161204');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MAC MAHON','Bd de VERDUN',NULL,NULL,'351296.82','6790077.73','-1.68876202','48.11786642');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'PARC OBERTHUR','Parc Hamelin Oberthür','Horaires du Parc',FALSE,'353484.29','6789357.70','-1.65884647','48.11256303');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Thabord GRIGNAN','1 rue de Grignan','Horaires du Parc',TRUE,'352557.91','6789737.44','-1.67157417','48.11548189');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Thabord NOTRE DAME','1 place St. Melaine parc','Horaires du Parc',FALSE,'352398.37','6789648.62','-1.67364372','48.11459915');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Thabord Parc','Coté Bd. de la Duchesse Anne','Horaires du Parc',TRUE,'352947.86','6789538.34','-1.66618524','48.11390082');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Thabord PARIS','1 rue de Paris','Horaires du Parc',FALSE,'352589.17','6789381.04','-1.67087172','48.11229712');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'DUCHESSE ANNE','Face au 3 Bd. de la Duchesse Anne','8h15 à 16h15',FALSE,'352946.90','6789420.03','-1.66610433','48.11283760');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Esplanade G. De GAULLE','Esplanade Gl. De Gaulle','6h à 23h',TRUE,'352070.53','6788770.42','-1.67734269','48.10653599');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'HOCHE','Face au 10 place Hoche et sous-terrain','8h à 1h45',TRUE,'352144.24','6789737.44','-1.67712334','48.11526166');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Saint GERMAINS','2 place St. Germains',NULL,TRUE,'352174.47','6789193.87','-1.67628543','48.11039509');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Sainte ANNE','Face au 15 place Ste.Anne',NULL,TRUE,'351858.05','6789661.85','-1.68090208','48.11443015');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Place de BRETAGNE','8 place de Bretagne','6h à 23h',TRUE,'351603.51','6789068.97','-1.68384415','48.10896878');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'REPUBLIQUE','Face 1 Quai Lamennais','24h / 24h',TRUE,'351861.03','6789173.05','-1.68047298','48.11004102');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Colombia','rez de chaussé Colombia',NULL,NULL,'351755.35','6788591.16','-1.68142727','48.10475775');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'APIGNE','Parking 2 Chemin rural 72','TLJ',TRUE,'346889.14','6787621.36','-1.74590428','48.09343413');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'APIGNE','Parking 1 Chemin rural 72','TLJ',TRUE,'347364.77','6787451.06','-1.73938983','48.09216136');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'JUILLET','Face au 5 rue de Juillet','6h à 23h',FALSE,'351584.76','6789451.29','-1.68440017','48.11239303');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Charles GENIAUX','Square Charles Géniaux',NULL,NULL,'349536.02','6789454.81','-1.71188287','48.11132840');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MITTERRAND','Mail Mitterrand',NULL,NULL,'351299.99','6789185.22','-1.68800772','48.10985105');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'COLOMBIER','22 place du Colombier','6h à 23h',TRUE,'352001.87','6788601.12','-1.67812898','48.10497866');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Salle / CITE','10 rue St. Louis','Horaires de la salle',FALSE,'351767.77','6789666.63','-1.68211683','48.11442496');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'T.N.B.','rue Saint-Hélier','Horaires du théâtre',TRUE,'352434.81','6788878.89','-1.67254321','48.10770431');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'TOULLIER','2 rue Toullier','6h à 23h',FALSE,'352244.66','6789071.65','-1.67524686','48.10933463');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'MUSEE BEAUX ARTS','32 rue Hoches',NULL,NULL,'352139.10','6789645.02','-1.67711865','48.11442882');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Le LIBERTE','1 Pl. Esplanade Gl. De Gaulle','Horaires de la salle',NULL,'352103.87','6788787.25','-1.67690904','48.10670495');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Parc St CYR','Rue Papu',NULL,NULL,'350513.77','6789371.82','-1.69870206','48.11110691');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Musée des Beaux-Arts','20 quai Emile Zola',NULL,NULL,'352267.27','6789102.18','-1.67496778','48.10962086');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BREQUIGNY Piscine','Bd. Albert Premier','6h à 23h',TRUE,'351090.03','6786877.95','-1.68898410','48.08901354');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'SARAH BERNHARDT','Square Sarah Bernhardt','Jour de marché',FALSE,'351687.72','6787109.44','-1.68115477','48.09141186');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'CHAMP MANCEAU','Rue Louis et René Moines','6h à 23h',TRUE,'351587.30','6787115.70','-1.68250615','48.09141454');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BREQUIGNY Parc','Allée Ukraine côté Novotel','6h à 23h',TRUE,'351508.85','6786007.61','-1.68267597','48.08141891');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ALBERT BAYET','Place Albert Bayet','6h à 23h',TRUE,'350819.07','6786508.12','-1.69232188','48.08554668');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BREQUIGNY Angleterre','84 rue d''Angleterre',NULL,NULL,'350856.08','6786228.23','-1.69160249','48.08305230');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'BLOSNE','Espace Social Ty-Blosne 7 Bd. Yougoslavie','du L au V de 8h30 à 18h, sauf mardi matin',NULL,'353376.06','6786674.45','-1.65817257','48.08840234');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Le TRIANGLE','1 bd de Yougoslavie','Horaires de l''établissement',NULL,'353248.54','6786711.56','-1.65991170','48.08866799');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ITALIE','Avenue d''Italie',NULL,NULL,'352672.10','6786608.05','-1.66755816','48.08743205');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'Paul LAFARGUE','49 avenue des Pays Bas','6h à 23h',TRUE,'353163.33','6786149.92','-1.66060914','48.08357765');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'HOPITAL SUD','16 bd de Bulgarie',NULL,TRUE,'353711.15','6786096.30','-1.65322242','48.08338653');
INSERT INTO toilettes(id_toilettes,description,nom,lieu,horaires,pmr,x93,y93,x84,y84) VALUES (NULL,NULL,'ZAGREB','Place de Zagreb','Samedi jour de marché',TRUE,'353496.36','6786637.74','-1.65653051','48.08813637');


CREATE VIEW fiches (id , nom , description, lieu ,horaire, pmr ,moyenne_proprete,moyenne_equipement,moyenne_accessibilite )
AS SELECT id_toilettes,
t.nom ,
t.description,
t.lieu,
t.horaires,
t.pmr,
AVG(proprete),
AVG(equipement),
AVG(accessibilite)
FROM toilettes t, notes n
WHERE t.id_toilettes=n.id_toilettes_notes
GROUP BY t.id_toilettes;