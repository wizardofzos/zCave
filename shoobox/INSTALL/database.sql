# The Shoobox table. This contains all our documents.


CREATE TABLE  `shoobox`.`items` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT COMMENT  'unique identifier for an item',
`box` INT( 10 ) NOT NULL DEFAULT  '1' COMMENT  'Box number. 1 for todo, 2 for done. furute expansions possible',
`value` FLOAT( 3 ) NOT NULL DEFAULT  '0' COMMENT  'Value, positive=income, negative=expenses',
`tax` FLOAT( 2 ) NOT NULL DEFAULT  '0' COMMENT  'tax included with the value in percentages ',
`type` VARCHAR( 255 ) NOT NULL COMMENT  'type of payment, free format text used for grouping',
`description` VARCHAR( 255 ) NULL COMMENT  'Free format text describing the payment in more detail if needed',
`documentname` VARCHAR( 255 ) NOT NULL COMMENT  'documentname',
`date` DATE NOT NULL COMMENT  'date the document was placed in box',
PRIMARY KEY (  `id` ) ,
INDEX (  `box` ,  `type` ) ,
UNIQUE (
`id`
)
) ENGINE = MYISAM