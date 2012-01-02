ALTER TABLE wcf1_clique_menu_item 
    ADD UNIQUE (menuItem);

ALTER TABLE wcf1_clique
    ADD membershipEnable TINYINT (1) NOT NULL DEFAULT 1;