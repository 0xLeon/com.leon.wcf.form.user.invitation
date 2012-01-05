ALTER TABLE wcf1_user DROP INDEX invitationCode;
ALTER TABLE wcf1_user MODIFY invitationCode INT(10) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD INDEX (invitationCode);

ALTER TABLE wcf1_user_invitation DROP INDEX code;
ALTER TABLE wcf1_user_invitation MODIFY code INT(10) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user_invitation ADD INDEX (code);
