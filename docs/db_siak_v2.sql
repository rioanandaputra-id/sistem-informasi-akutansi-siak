DROP TABLE IF EXISTS "public"."akun";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."akun" (
    "id_akun" uuid NOT NULL,
    "no_akun" varchar(10) NOT NULL,
    "nm_akun" varchar(255) NOT NULL,
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
    "id_updater" uuid,
    PRIMARY KEY ("id_bku")
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
    "updated_at" timestamp,
    PRIMARY KEY ("id_detail_laksana_kegiatan")
);

DROP TABLE IF EXISTS "public"."detail_rba";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."detail_rba" (
    "id_detail_rba" uuid NOT NULL,
    "id_rba" uuid NOT NULL,
    "id_akun" uuid NOT NULL,
    "vol" int4 NOT NULL,
    "satuan" varchar(255) NOT NULL,
    "indikator" int4,
    "tarif" int8 NOT NULL,
    "total" int8 NOT NULL,
    "a_setuju" bpchar(1) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    PRIMARY KEY ("id_detail_rba")
);

DROP TABLE IF EXISTS "public"."divisi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."divisi" (
    "id_divisi" uuid NOT NULL,
    "nm_divisi" varchar(255) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    PRIMARY KEY ("id_divisi")
);

DROP TABLE IF EXISTS "public"."kegiatan";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."kegiatan" (
    "id_kegiatan" uuid NOT NULL,
    "id_program" uuid,
    "nm_kegiatan" text NOT NULL,
    "a_aktif" bpchar(1) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    PRIMARY KEY ("id_kegiatan")
);

DROP TABLE IF EXISTS "public"."kegiatan_divisi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."kegiatan_divisi" (
    "id_kegiatan_divisi" uuid NOT NULL,
    "id_divisi" uuid NOT NULL,
    "id_kegiatan" uuid NOT NULL,
    "a_verif_rba" bpchar(1) NOT NULL,
    "id_verif_rba" uuid,
    "catatan" text,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updated" uuid,
    PRIMARY KEY ("id_kegiatan_divisi")
);

DROP TABLE IF EXISTS "public"."laksana_kegiatan";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."laksana_kegiatan" (
    "id_laksana_kegiatan" uuid NOT NULL,
    "id_kegiatan_divisi" uuid NOT NULL,
    "tgl_ajuan" timestamp NOT NULL,
    "a_verif_kabag_keuangan" bpchar(1),
    "id_verif_kabag_keuangan" uuid,
    "tgl_verif_kabag_keuangan" timestamp,
    "catatan" text,
    "waktu_pelaksanaan" timestamp NOT NULL,
    "waktu_selesai" timestamp NOT NULL,
    "tahun" varchar(4) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    PRIMARY KEY ("id_laksana_kegiatan")
);

DROP TABLE IF EXISTS "public"."misi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."misi" (
    "id_misi" uuid NOT NULL,
    "nm_misi" varchar(255) NOT NULL,
    "periode" varchar(4) NOT NULL,
    "a_aktif" bpchar(1) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    PRIMARY KEY ("id_misi")
);

DROP TABLE IF EXISTS "public"."program";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."program" (
    "id_program" uuid NOT NULL,
    "nm_program" varchar(255) NOT NULL,
    "periode" varchar(4) NOT NULL,
    "a_aktif" bpchar(1) NOT NULL,
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
    "a_verif_rba" bpchar(1),
    "id_verif_rba" uuid,
    "tgl_verif_rba" timestamp,
    "a_verif_wilayah" bpchar(1),
    "id_verif_wilayah" uuid,
    "tgl_verif_wilayah" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updated" uuid,
    PRIMARY KEY ("id_rba")
);

DROP TABLE IF EXISTS "public"."spj";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."spj" (
    "id_spj" uuid NOT NULL,
    "id_laksana_kegiatan" uuid NOT NULL,
    "a_verif_bendahara_pengeluaran" bpchar(1),
    "id_verif_bendahara_pengeluaran" uuid,
    "tgl_verif_bendahara_pengeluaran" timestamp,
    "a_verif_kabag_keuangan" bpchar(1),
    "id_verif_kabag_keuangan" uuid,
    "tgl_verif_kabag_keuangan" timestamp,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    "id_updater" uuid,
    PRIMARY KEY ("id_spj")
);

DROP TABLE IF EXISTS "public"."visi";
-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Table Definition
CREATE TABLE "public"."visi" (
    "id_visi" uuid NOT NULL,
    "nm_visi" varchar(255) NOT NULL,
    "periode" varchar(4) NOT NULL,
    "a_aktif" bpchar(1) NOT NULL,
    "created_at" timestamp,
    "updated_at" timestamp,
    "deleted_at" timestamp,
    PRIMARY KEY ("id_visi")
);

