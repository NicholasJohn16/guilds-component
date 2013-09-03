# Add usernames to all members
UPDATE jos_users, jos_guilds_members
SET jos_guilds_members.username = jos_users.username
WHERE jos_guilds_members.user_id = jos_users.id

# Update sto_handle to @handles
UPDATE jos_community_fields_values, jos_guilds_members
SET jos_guilds_members.sto_handle = jos_community_fields_values.value
WHERE jos_guilds_members.user_id = jos_community_fields_values.user_id
AND jos_community_fields_values.field_id = 29

# UPdate tor_handle with TOR forum name
UPDATE jos_community_fields_values, jos_guilds_members
SET jos_guilds_members.sto_handle = jos_community_fields_values.value
WHERE jos_guilds_members.user_id = jos_community_fields_values.user_id
AND jos_community_fields_values.field_id = 39

UPDATE jos_guilds_members SET sto_handle = NULL WHERE sto_handle = "";
UPDATE jos_guilds_members SET gw2_handle = NULL WHERE gw2_handle = "";
UPDATE jos_guilds_members SET tor_handle = NULL WHERE tor_handle = "";