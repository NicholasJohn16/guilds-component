# Add usernames to all members
# Shouldn't be needed cause it's been added to the install sql
# UPDATE jos_users, jos_guilds_members
# SET jos_guilds_members.username = jos_users.username
# WHERE jos_guilds_members.user_id = jos_users.id

# Update sto_handle to @handles
UPDATE jos_community_fields_values, jos_guilds_members
SET jos_guilds_members.sto_handle = jos_community_fields_values.value
WHERE jos_guilds_members.user_id = jos_community_fields_values.user_id
AND jos_community_fields_values.field_id = 29;

# Update tor_handle with TOR forum name
UPDATE jos_community_fields_values, jos_guilds_members
SET jos_guilds_members.tor_handle = jos_community_fields_values.value
WHERE jos_guilds_members.user_id = jos_community_fields_values.user_id
AND jos_community_fields_values.field_id = 39;

# Update tor_handle with GW2 User ID
UPDATE jos_community_fields_values, jos_guilds_members
SET jos_guilds_members.gw2_handle = jos_community_fields_values.value
WHERE jos_guilds_members.user_id = jos_community_fields_values.user_id
AND jos_community_fields_values.field_id = 41;

UPDATE jos_guilds_members SET sto_handle = NULL WHERE sto_handle = "";
UPDATE jos_guilds_members SET gw2_handle = NULL WHERE gw2_handle = "";
UPDATE jos_guilds_members SET tor_handle = NULL WHERE tor_handle = "";

UPDATE adv_user_manager, jos_guilds_members
SET jos_guilds_members.appdate = adv_user_manager.appdate,
jos_guilds_members.status = adv_user_manager.status,
jos_guilds_members.notes = adv_user_manager.notes,
jos_guilds_members.edit_id = jos_guilds_members.edit_time
WHERE jos_guilds_members.user_id = adv_user_manager.user_id;

INSERT INTO `jos_guilds_characters` (
    `user_id`,
    `name`,
    `guild`,
    `game`,
    `allegiance`,
    `class`,
    `checked`,
    `published`,
    `unpublished_date`,
    `created_by`,
    `created_on`
) SELECT 
    `user_id`,
    `name`,
    `guild`,
    `game`,
    `allegiance`,
    `class`,
    `rosterchecked`,
    `published`,
    `unpublisheddate`,
    `created_by`,
    `created_on` 
FROM `adv_character_names` 
ORDER BY `id` asc;

UPDATE `jos_guilds_characters` SET `game` = 3 WHERE `game` = 2;
UPDATE `jos_guilds_characters` SET `game` = 2 WHERE `game` = 1;

UPDATE `jos_guilds_characters` SET `allegiance` = 7 WHERE `allegiance` = 4;
UPDATE `jos_guilds_characters` SET `allegiance` = 6 WHERE `allegiance` = 3;
UPDATE `jos_guilds_characters` SET `allegiance` = 5 WHERE `allegiance` = 2;
UPDATE `jos_guilds_characters` SET `allegiance` = 4 WHERE `allegiance` = 1;

UPDATE `jos_guilds_characters` SET `class` = 18 WHERE `class` = 11;
UPDATE `jos_guilds_characters` SET `class` = 17 WHERE `class` = 10;
UPDATE `jos_guilds_characters` SET `class` = 16 WHERE `class` = 9;
UPDATE `jos_guilds_characters` SET `class` = 15 WHERE `class` = 8;
UPDATE `jos_guilds_characters` SET `class` = 14 WHERE `class` = 7;
UPDATE `jos_guilds_characters` SET `class` = 13 WHERE `class` = 6;
UPDATE `jos_guilds_characters` SET `class` = 12 WHERE `class` = 5;
UPDATE `jos_guilds_characters` SET `class` = 11 WHERE `class` = 4;
UPDATE `jos_guilds_characters` SET `class` = 10 WHERE `class` = 3;
UPDATE `jos_guilds_characters` SET `class` = 9  WHERE `class` = 2;
UPDATE `jos_guilds_characters` SET `class` = 8  WHERE `class` = 1;

UPDATE `jos_guilds_characters` SET `guild` = 19 WHERE `guild` = 2;
UPDATE `jos_guilds_characters` SET `guild` = 20 WHERE `guild` = 3;
UPDATE `jos_guilds_characters` SET `guild` = 21 WHERE `guild` = 4;
UPDATE `jos_guilds_characters` SET `guild` = 29 WHERE `guild` = 5;
UPDATE `jos_guilds_characters` SET `guild` = 1 WHERE `guild` = 6 AND `guild` = 7 AND `guild` = 8;
UPDATE `jos_guilds_characters` SET `guild` = 23 WHERE `guild` = 9;
UPDATE `jos_guilds_characters` SET `guild` = 24 WHERE `guild` = 10;
UPDATE `jos_guilds_characters` SET `guild` = 25 WHERE `guild` = 11;
UPDATE `jos_guilds_characters` SET `guild` = 26 WHERE `guild` = 12;
UPDATE `jos_guilds_characters` SET `guild` = 22 WHERE `guild` = 13;