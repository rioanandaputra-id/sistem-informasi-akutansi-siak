DROP TABLE IF EXISTS "public"."akun";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."akun" (
    "id_akun" uuid NOT NULL,
    "no_akun" varchar NOT NULL,
    "nm_akun" varchar NOT NULL,
    "keterangan" text,
    "sumber_akun" uuid,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    PRIMARY KEY ("id_akun")
);

DROP TABLE IF EXISTS "public"."bku";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."bku" (
    "id_bku" uuid NOT NULL,
    "id_bagian" uuid NOT NULL,
    "id_laksana_kegiatan" uuid NOT NULL,
    "tanggal" date NOT NULL,
    "id_akun" uuid NOT NULL,
    "masuk" int8,
    "keluar" int8,
    "saldo" int8 NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."detail_laksana_kegiatan";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."detail_laksana_kegiatan" (
    "id_detail_laksana_kegiatan" uuid NOT NULL,
    "id_laksana_kegiatan" uuid NOT NULL,
    "id_detail_rba" uuid NOT NULL,
    "jumlah" int2 NOT NULL,
    "total" int8 NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp
);

DROP TABLE IF EXISTS "public"."detail_rba";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."detail_rba" (
    "id_detail_rba" uuid NOT NULL,
    "id_rba" uuid NOT NULL,
    "id_akun" uuid NOT NULL,
    "vol" int4 NOT NULL,
    "satuan" varchar NOT NULL,
    "indikator" int4,
    "tarif" int8 NOT NULL,
    "total" int8 NOT NULL,
    "a_setuju" bpchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp
);

DROP TABLE IF EXISTS "public"."divisi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."divisi" (
    "id_divisi" uuid NOT NULL,
    "nm_divisi" varchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."failed_jobs";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Squences
CREATE SEQUENCE IF NOT EXISTS failed_jobs_id_seq

-- Table Definition
CREATE TABLE "public"."failed_jobs" (
    "id" int8 NOT NULL DEFAULT nextval('failed_jobs_id_seq'::regclass),
    "uuid" varchar NOT NULL,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL,
    "exception" text NOT NULL,
    "failed_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS "public"."kegiatan";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."kegiatan" (
    "id_kegiatan" uuid NOT NULL,
    "id_program" uuid,
    "nm_kegiatan" text NOT NULL,
    "a_aktif" bpchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."kegiatan_divisi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."kegiatan_divisi" (
    "id_kegiatan_divisi" uuid NOT NULL,
    "id_divisi" uuid NOT NULL,
    "id_kegiatan" uuid NOT NULL,
    "a_verif_rba" bpchar NOT NULL,
    "id_verif_rba" uuid,
    "catatan" text,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updated" uuid
);

DROP TABLE IF EXISTS "public"."laksana_kegiatan";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."laksana_kegiatan" (
    "id_laksana_kegiatan" uuid NOT NULL,
    "id_kegiatan_divisi" uuid NOT NULL,
    "tgl_ajuan" timestamp NOT NULL,
    "a_verif_kabag_keuangan" bpchar,
    "id_verif_kabag_keuangan" uuid,
    "tgl_verif_kabag_keuangan" timestamp,
    "catatan" text,
    "waktu_pelaksanaan" timestamp NOT NULL,
    "waktu_selesai" timestamp NOT NULL,
    "tahun" varchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."migrations";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Squences
CREATE SEQUENCE IF NOT EXISTS migrations_id_seq

-- Table Definition
CREATE TABLE "public"."migrations" (
    "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
    "migration" varchar NOT NULL,
    "batch" int4 NOT NULL
);

DROP TABLE IF EXISTS "public"."misi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."misi" (
    "id_misi" uuid NOT NULL,
    "nm_misi" varchar NOT NULL,
    "periode" varchar NOT NULL,
    "a_aktif" bpchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    PRIMARY KEY ("id_misi")
);

DROP TABLE IF EXISTS "public"."password_resets";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."password_resets" (
    "email" varchar NOT NULL,
    "token" varchar NOT NULL,
    "created_at" timestamp
);

DROP TABLE IF EXISTS "public"."pengguna";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."pengguna" (
    "id_pengguna" uuid NOT NULL,
    "username" varchar NOT NULL,
    "password" varchar NOT NULL,
    "nm_pengguna" varchar NOT NULL,
    "jk" bpchar,
    "no_hp" varchar,
    "alamat" text,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."peran";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."peran" (
    "nm_peran" varchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "id_peran" int2 NOT NULL
);

DROP TABLE IF EXISTS "public"."peran_pengguna";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."peran_pengguna" (
    "id_peran_pengguna" uuid NOT NULL,
    "id_pengguna" uuid NOT NULL,
    "id_peran" int2 NOT NULL,
    "a_aktif" bpchar NOT NULL,
    "last_active" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."personal_access_tokens";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Squences
CREATE SEQUENCE IF NOT EXISTS personal_access_tokens_id_seq

-- Table Definition
CREATE TABLE "public"."personal_access_tokens" (
    "id" int8 NOT NULL DEFAULT nextval('personal_access_tokens_id_seq'::regclass),
    "tokenable_type" varchar NOT NULL,
    "tokenable_id" int8 NOT NULL,
    "name" varchar NOT NULL,
    "token" varchar NOT NULL,
    "abilities" text,
    "last_used_at" timestamp,
    "expires_at" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp
);

DROP TABLE IF EXISTS "public"."program";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."program" (
    "id_program" uuid NOT NULL,
    "nm_program" varchar NOT NULL,
    "periode" varchar NOT NULL,
    "a_aktif" bpchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    "id_misi" uuid NOT NULL,
    PRIMARY KEY ("id_program")
);

DROP TABLE IF EXISTS "public"."rba";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."rba" (
    "id_rba" uuid NOT NULL,
    "tgl_buat" timestamp NOT NULL,
    "tgl_submit" timestamp,
    "catatan" text,
    "id_kegiatan_divisi" uuid NOT NULL,
    "a_verif_rba" bpchar,
    "id_verif_rba" uuid,
    "tgl_verif_rba" timestamp,
    "a_verif_wilayah" bpchar,
    "id_verif_wilayah" uuid,
    "tgl_verif_wilayah" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updated" uuid
);

DROP TABLE IF EXISTS "public"."roles";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Squences
CREATE SEQUENCE IF NOT EXISTS roles_id_role_seq

-- Table Definition
CREATE TABLE "public"."roles" (
    "id_role" int8 NOT NULL DEFAULT nextval('roles_id_role_seq'::regclass),
    "role_name" varchar NOT NULL,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."spj";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."spj" (
    "id_spj" uuid NOT NULL,
    "id_laksana_kegiatan" uuid NOT NULL,
    "a_verif_bendahara_pengeluaran" bpchar,
    "id_verif_bendahara_pengeluaran" uuid,
    "tgl_verif_bendahara_pengeluaran" timestamp,
    "a_verif_kabag_keuangan" bpchar,
    "id_verif_kabag_keuangan" uuid,
    "tgl_verif_kabag_keuangan" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."users";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."users" (
    "id_user" uuid NOT NULL,
    "id_role" int8 NOT NULL,
    "a_active" int2 NOT NULL DEFAULT '0'::smallint,
    "full_name" varchar NOT NULL,
    "gender" bpchar NOT NULL,
    "username" varchar NOT NULL,
    "password" varchar NOT NULL,
    "phone" varchar,
    "email" varchar NOT NULL,
    "address" text,
    "remember_token" varchar,
    "email_verified_at" timestamp,
    "created_at" timestamp NOT NULL,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);

DROP TABLE IF EXISTS "public"."visi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."visi" (
    "id_visi" uuid NOT NULL,
    "nm_visi" varchar NOT NULL,
    "periode" varchar NOT NULL,
    "a_aktif" bpchar NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid
);



















INSERT INTO "public"."migrations" ("id", "migration", "batch") VALUES
(43, '2014_10_12_000000_create_users_table', 1);
INSERT INTO "public"."migrations" ("id", "migration", "batch") VALUES
(44, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO "public"."migrations" ("id", "migration", "batch") VALUES
(45, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO "public"."migrations" ("id", "migration", "batch") VALUES
(46, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(47, '2022_11_13_203452_create_roles_table', 1);

INSERT INTO "public"."misi" ("id_misi", "nm_misi", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('f56d8418-71d0-4f02-b206-37eb7f608039', '1111', '1111', '1', '2022-11-14 03:23:58', '2022-11-14 03:25:24', '2022-11-14 03:25:24', '0c289cce-4442-4c93-8923-b9c816dd17ed');
INSERT INTO "public"."misi" ("id_misi", "nm_misi", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('4350e33f-8a63-4319-9de9-2cd6790ea2bc', '1111', '1111', '1', '2022-11-14 03:58:51', '2022-11-14 03:58:51', NULL, '0c289cce-4442-4c93-8923-b9c816dd17ed');












INSERT INTO "public"."program" ("id_program", "nm_program", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater", "id_misi") VALUES
('fd6396da-8555-4f4f-aff5-5e5a4b69377a', 'eeeee', '1111', '1', '2022-11-14 04:03:41', '2022-11-14 04:09:10', '2022-11-14 04:09:10', '0c289cce-4442-4c93-8923-b9c816dd17ed', '4350e33f-8a63-4319-9de9-2cd6790ea2bc');
INSERT INTO "public"."program" ("id_program", "nm_program", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater", "id_misi") VALUES
('09e3d170-3300-4352-af7b-5ccd462be2a2', 'eeeee', '1111', '1', '2022-11-14 04:08:59', '2022-11-14 04:09:10', '2022-11-14 04:09:10', '0c289cce-4442-4c93-8923-b9c816dd17ed', '4350e33f-8a63-4319-9de9-2cd6790ea2bc');
INSERT INTO "public"."program" ("id_program", "nm_program", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater", "id_misi") VALUES
('2d76d912-3511-42a6-b9c7-efe15a12b78f', 'eeeee', '1111', '1', '2022-11-14 04:09:03', '2022-11-14 04:09:10', '2022-11-14 04:09:10', '0c289cce-4442-4c93-8923-b9c816dd17ed', '4350e33f-8a63-4319-9de9-2cd6790ea2bc');
INSERT INTO "public"."program" ("id_program", "nm_program", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater", "id_misi") VALUES
('2c7d896f-4645-4834-a433-c546856d1ce6', '1111', '1111', '1', '2022-11-14 04:09:22', '2022-11-14 04:11:42', NULL, '0c289cce-4442-4c93-8923-b9c816dd17ed', '4350e33f-8a63-4319-9de9-2cd6790ea2bc');



INSERT INTO "public"."roles" ("id_role", "role_name", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
(1, 'Kepala PMI Wilayah/Kuasa', '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."roles" ("id_role", "role_name", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
(2, 'Kepala UDD', '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."roles" ("id_role", "role_name", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
(3, 'Koordinator TIM RBA', '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."roles" ("id_role", "role_name", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
(4, 'Kepala Departemen/Ka Bagian', '2022-11-14 00:37:55', NULL, NULL, NULL),
(5, 'Bendahara Penerimaan', '2022-11-14 00:37:55', NULL, NULL, NULL),
(6, 'Bendahara Pengeluran', '2022-11-14 00:37:55', NULL, NULL, NULL),
(7, 'Bendahara Kegiatan/Panitia Pelaksana', '2022-11-14 00:37:55', NULL, NULL, NULL),
(99, 'Developer', '2022-11-14 00:37:55', NULL, NULL, NULL);



INSERT INTO "public"."users" ("id_user", "id_role", "a_active", "full_name", "gender", "username", "password", "phone", "email", "address", "remember_token", "email_verified_at", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('e1333e2a-40bf-4ad7-94d5-22d72f6c4e69', 1, 1, 'Kepala PMI Wilayah/Kuasa', 'L', 'kepalapmikuasa', '$2y$10$kWKocZdz4ddrEwUYh73xt.34A20V.KnZCzgkK0Ar6kPQKC0WlJSRq', '0', 'kepalapmikuasa@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."users" ("id_user", "id_role", "a_active", "full_name", "gender", "username", "password", "phone", "email", "address", "remember_token", "email_verified_at", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('0c289cce-4442-4c93-8923-b9c816dd17ed', 2, 1, 'Kepla UDD', 'L', 'kepalauud', '$2y$10$TbyCrEFl.O9s1GQg04pS1uXoQ7x7gokfU9jgiwLyQhcx9R4lnV44m', '0', 'kepalauud@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."users" ("id_user", "id_role", "a_active", "full_name", "gender", "username", "password", "phone", "email", "address", "remember_token", "email_verified_at", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('c640133b-a0ce-408f-a563-31405371ecf1', 3, 1, 'Koordinator TIM RBA', 'L', 'kordinatortimrba', '$2y$10$s6NIhAXm4RJANcF/CaENQeTTFuykOIfiXI59l5dRGvr/e43MOKjKm', '0', 'kordinatortimrba@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL);
INSERT INTO "public"."users" ("id_user", "id_role", "a_active", "full_name", "gender", "username", "password", "phone", "email", "address", "remember_token", "email_verified_at", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('f0bc8307-0005-47d3-9e60-bdeca5c5dc07', 4, 1, 'Kepala Departemen/Ka Bagian', 'L', 'kepaladepartemenkabagian', '$2y$10$2PknPouurrCpMA0LyPekiOd0uHyT9tu.J9MVWiTjEgHqzX9WRUVsi', '0', 'kepaladepartemenkabagian@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL),
('bdfd7d92-7b58-4005-ac6a-d194c8e1f3a7', 5, 1, 'Bendahara Penerimaan', 'L', 'bendaharapenerimaan', '$2y$10$jT290lfcLY/dxNMEeaVs.OYNMEsR5MbwCgSAnbBwlFYMDQSCaYEw2', '0', 'bendaharapenerimaan@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL),
('9c2f46e8-b86b-474f-bfdf-5ad80c564107', 6, 1, 'Bendahara Pengeluran', 'L', 'bendaharapengeluaran', '$2y$10$rH/7B5J5fDikRKkfmoVaU.XjXPLBVyoUzQNcWNVPpbF5z4godLbLS', '0', 'bendaharapengeluaran@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL),
('f13d4957-eaf3-4a89-981a-f57364488867', 7, 1, 'Bendahara Kegiatan/Panitia Pelaksana', 'L', 'bendaharakegiatanpanitiapelaksana', '$2y$10$R1u1upgpw.hMdRF1ZSR18OXmkEYl/twE5Sv2D7BJk6YxIT5GsiiH2', '0', 'bendaharakegiatanpanitiapelaksana@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL),
('39ed54c8-9457-4cf9-b9d9-2483ea644d03', 99, 1, 'Developer', 'L', 'developer', '$2y$10$fSEGtNvKrvmKU9Ea/DHtrOW4O/kUYRTvvhwTUPmpUajDxCRHRsOuy', '0', 'developer@siak.com', '-', NULL, NULL, '2022-11-14 00:37:55', NULL, NULL, NULL);

INSERT INTO "public"."visi" ("id_visi", "nm_visi", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('6239e4f3-44e8-45f7-99ea-26266406dadb', '1111', '1111', '2', '2022-11-14 02:58:10', '2022-11-14 02:58:10', NULL, '0c289cce-4442-4c93-8923-b9c816dd17ed');
INSERT INTO "public"."visi" ("id_visi", "nm_visi", "periode", "a_aktif", "created_at", "updated_at", "deleted_at", "id_updater") VALUES
('55119141-a043-40d2-a91c-24137610dd6e', '2222', '2222', '1', '2022-11-14 02:58:15', '2022-11-14 03:27:38', NULL, '0c289cce-4442-4c93-8923-b9c816dd17ed');

