PGDMP     5            	    
    z            pmi    11.0    11.0 ,    O           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                       false            P           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            Q           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                       false            R           1262    221464    pmi    DATABASE     ?   CREATE DATABASE pmi WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'English_Indonesia.1252' LC_CTYPE = 'English_Indonesia.1252';
    DROP DATABASE pmi;
             postgres    false                        2615    2200    akses    SCHEMA        CREATE SCHEMA akses;
    DROP SCHEMA akses;
             HP    false            S           0    0    SCHEMA akses    COMMENT     5   COMMENT ON SCHEMA akses IS 'standard public schema';
                  HP    false    3                        2615    221551 	   akuntansi    SCHEMA        CREATE SCHEMA akuntansi;
    DROP SCHEMA akuntansi;
             postgres    false            	            2615    221483 	   manajemen    SCHEMA        CREATE SCHEMA manajemen;
    DROP SCHEMA manajemen;
             HP    false            ?            1259    221465    pengguna    TABLE     ?  CREATE TABLE akses.pengguna (
    id_pengguna uuid NOT NULL,
    username character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    nm_pengguna character varying(255) NOT NULL,
    jk character(1),
    alamat text,
    no_hp character varying(15),
    a_aktif character(1),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE akses.pengguna;
       akses         postgres    false    3            ?            1259    221473    peran    TABLE     ?   CREATE TABLE akses.peran (
    id_peran smallint NOT NULL,
    nm_peran character varying(255) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);
    DROP TABLE akses.peran;
       akses         postgres    false    3            ?            1259    221478    role_pengguna    TABLE     D  CREATE TABLE akses.role_pengguna (
    id_role_pengguna uuid NOT NULL,
    id_pengguna uuid NOT NULL,
    id_peran smallint NOT NULL,
    a_aktif character(1) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
     DROP TABLE akses.role_pengguna;
       akses         postgres    false    3            ?            1259    221552    coa    TABLE     $  CREATE TABLE akuntansi.coa (
    id_coa uuid NOT NULL,
    nm_coa character varying(255) NOT NULL,
    id_sub_coa uuid,
    uraian text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE akuntansi.coa;
    	   akuntansi         postgres    false    7            ?            1259    221569    coa_transaction    TABLE     ?  CREATE TABLE akuntansi.coa_transaction (
    id_coa_transaction uuid NOT NULL,
    id_coa uuid NOT NULL,
    total bigint NOT NULL,
    tgl timestamp without time zone NOT NULL,
    a_keluar character(1),
    a_masuk character(1),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid,
    id_depart_keg uuid
);
 &   DROP TABLE akuntansi.coa_transaction;
    	   akuntansi         postgres    false    7            ?            1259    221484    depart    TABLE       CREATE TABLE manajemen.depart (
    id_depart uuid NOT NULL,
    nm_depart character varying(255) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE manajemen.depart;
    	   manajemen         postgres    false    9            ?            1259    221505 
   depart_keg    TABLE     )  CREATE TABLE manajemen.depart_keg (
    id_depart_keg uuid NOT NULL,
    id_depart uuid NOT NULL,
    id_prog uuid,
    id_keg uuid NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
 !   DROP TABLE manajemen.depart_keg;
    	   manajemen         postgres    false    9            ?            1259    221497    keg    TABLE     y  CREATE TABLE manajemen.keg (
    id_keg uuid NOT NULL,
    nm_keg text NOT NULL,
    tgl_mulai timestamp without time zone NOT NULL,
    tgl_selesai timestamp without time zone NOT NULL,
    a_aktif character(1) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE manajemen.keg;
    	   manajemen         postgres    false    9            ?            1259    221489    prog    TABLE     ?   CREATE TABLE manajemen.prog (
    id_prog uuid NOT NULL,
    nm_prog text NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE manajemen.prog;
    	   manajemen         postgres    false    9            ?            1259    221516    rab_keg    TABLE     v  CREATE TABLE manajemen.rab_keg (
    id_rab_keg uuid NOT NULL,
    id_depart_keg uuid NOT NULL,
    uraian text NOT NULL,
    qty integer NOT NULL,
    satuan character varying(255) NOT NULL,
    total bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    deleted_at timestamp without time zone,
    id_updater uuid
);
    DROP TABLE manajemen.rab_keg;
    	   manajemen         postgres    false    9            ?            1259    221560    spj_keg    TABLE     s  CREATE TABLE manajemen.spj_keg (
    id_spj_keg uuid NOT NULL,
    id_rab_keg uuid NOT NULL,
    uraian text NOT NULL,
    qty integer NOT NULL,
    satuan character varying(255) NOT NULL,
    total bigint NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    id_updater uuid,
    deleted_at timestamp without time zone
);
    DROP TABLE manajemen.spj_keg;
    	   manajemen         postgres    false    9            ?            1259    221541 	   verif_keg    TABLE     C  CREATE TABLE manajemen.verif_keg (
    id_verif_keg uuid NOT NULL,
    id_depart_keg uuid NOT NULL,
    id_verif uuid NOT NULL,
    tgl_verif timestamp without time zone NOT NULL,
    catatan text,
    a_verif character(1) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);
     DROP TABLE manajemen.verif_keg;
    	   manajemen         postgres    false    9            A          0    221465    pengguna 
   TABLE DATA               ?   COPY akses.pengguna (id_pengguna, username, password, nm_pengguna, jk, alamat, no_hp, a_aktif, created_at, updated_at, deleted_at, id_updater) FROM stdin;
    akses       postgres    false    198   ?7       B          0    221473    peran 
   TABLE DATA               J   COPY akses.peran (id_peran, nm_peran, created_at, updated_at) FROM stdin;
    akses       postgres    false    199   ?7       C          0    221478    role_pengguna 
   TABLE DATA               ?   COPY akses.role_pengguna (id_role_pengguna, id_pengguna, id_peran, a_aktif, created_at, updated_at, deleted_at, id_updater) FROM stdin;
    akses       postgres    false    200   8       J          0    221552    coa 
   TABLE DATA               t   COPY akuntansi.coa (id_coa, nm_coa, id_sub_coa, uraian, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   akuntansi       postgres    false    207   8       L          0    221569    coa_transaction 
   TABLE DATA               ?   COPY akuntansi.coa_transaction (id_coa_transaction, id_coa, total, tgl, a_keluar, a_masuk, created_at, updated_at, deleted_at, id_updater, id_depart_keg) FROM stdin;
 	   akuntansi       postgres    false    209   ;8       D          0    221484    depart 
   TABLE DATA               i   COPY manajemen.depart (id_depart, nm_depart, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   manajemen       postgres    false    201   X8       G          0    221505 
   depart_keg 
   TABLE DATA               ?   COPY manajemen.depart_keg (id_depart_keg, id_depart, id_prog, id_keg, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   manajemen       postgres    false    204   u8       F          0    221497    keg 
   TABLE DATA               ?   COPY manajemen.keg (id_keg, nm_keg, tgl_mulai, tgl_selesai, a_aktif, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   manajemen       postgres    false    203   ?8       E          0    221489    prog 
   TABLE DATA               c   COPY manajemen.prog (id_prog, nm_prog, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   manajemen       postgres    false    202   ?8       H          0    221516    rab_keg 
   TABLE DATA               ?   COPY manajemen.rab_keg (id_rab_keg, id_depart_keg, uraian, qty, satuan, total, created_at, updated_at, deleted_at, id_updater) FROM stdin;
 	   manajemen       postgres    false    205   ?8       K          0    221560    spj_keg 
   TABLE DATA               ?   COPY manajemen.spj_keg (id_spj_keg, id_rab_keg, uraian, qty, satuan, total, created_at, updated_at, id_updater, deleted_at) FROM stdin;
 	   manajemen       postgres    false    208   ?8       I          0    221541 	   verif_keg 
   TABLE DATA               ?   COPY manajemen.verif_keg (id_verif_keg, id_depart_keg, id_verif, tgl_verif, catatan, a_verif, created_at, updated_at) FROM stdin;
 	   manajemen       postgres    false    206   9       ?
           2606    221472    pengguna pengguna_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY akses.pengguna
    ADD CONSTRAINT pengguna_pkey PRIMARY KEY (id_pengguna);
 ?   ALTER TABLE ONLY akses.pengguna DROP CONSTRAINT pengguna_pkey;
       akses         postgres    false    198            ?
           2606    221540    peran peran_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY akses.peran
    ADD CONSTRAINT peran_pkey PRIMARY KEY (id_peran);
 9   ALTER TABLE ONLY akses.peran DROP CONSTRAINT peran_pkey;
       akses         postgres    false    199            ?
           2606    221482     role_pengguna role_pengguna_pkey 
   CONSTRAINT     k   ALTER TABLE ONLY akses.role_pengguna
    ADD CONSTRAINT role_pengguna_pkey PRIMARY KEY (id_role_pengguna);
 I   ALTER TABLE ONLY akses.role_pengguna DROP CONSTRAINT role_pengguna_pkey;
       akses         postgres    false    200            ?
           2606    221559    coa coa_pkey 
   CONSTRAINT     Q   ALTER TABLE ONLY akuntansi.coa
    ADD CONSTRAINT coa_pkey PRIMARY KEY (id_coa);
 9   ALTER TABLE ONLY akuntansi.coa DROP CONSTRAINT coa_pkey;
    	   akuntansi         postgres    false    207            ?
           2606    221573 $   coa_transaction coa_transaction_pkey 
   CONSTRAINT     u   ALTER TABLE ONLY akuntansi.coa_transaction
    ADD CONSTRAINT coa_transaction_pkey PRIMARY KEY (id_coa_transaction);
 Q   ALTER TABLE ONLY akuntansi.coa_transaction DROP CONSTRAINT coa_transaction_pkey;
    	   akuntansi         postgres    false    209            ?
           2606    221575    depart_keg dep_keg_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY manajemen.depart_keg
    ADD CONSTRAINT dep_keg_pkey PRIMARY KEY (id_depart_keg);
 D   ALTER TABLE ONLY manajemen.depart_keg DROP CONSTRAINT dep_keg_pkey;
    	   manajemen         postgres    false    204            ?
           2606    221515    depart departemen_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY manajemen.depart
    ADD CONSTRAINT departemen_pkey PRIMARY KEY (id_depart);
 C   ALTER TABLE ONLY manajemen.depart DROP CONSTRAINT departemen_pkey;
    	   manajemen         postgres    false    201            ?
           2606    221513    keg kegiatan_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY manajemen.keg
    ADD CONSTRAINT kegiatan_pkey PRIMARY KEY (id_keg);
 >   ALTER TABLE ONLY manajemen.keg DROP CONSTRAINT kegiatan_pkey;
    	   manajemen         postgres    false    203            ?
           2606    221511    prog program_pkey 
   CONSTRAINT     W   ALTER TABLE ONLY manajemen.prog
    ADD CONSTRAINT program_pkey PRIMARY KEY (id_prog);
 >   ALTER TABLE ONLY manajemen.prog DROP CONSTRAINT program_pkey;
    	   manajemen         postgres    false    202            ?
           2606    221523    rab_keg rab_keg_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY manajemen.rab_keg
    ADD CONSTRAINT rab_keg_pkey PRIMARY KEY (id_rab_keg);
 A   ALTER TABLE ONLY manajemen.rab_keg DROP CONSTRAINT rab_keg_pkey;
    	   manajemen         postgres    false    205            ?
           2606    221567    spj_keg spj_keg_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY manajemen.spj_keg
    ADD CONSTRAINT spj_keg_pkey PRIMARY KEY (id_spj_keg);
 A   ALTER TABLE ONLY manajemen.spj_keg DROP CONSTRAINT spj_keg_pkey;
    	   manajemen         postgres    false    208            ?
           2606    221550    verif_keg ver_keg_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY manajemen.verif_keg
    ADD CONSTRAINT ver_keg_pkey PRIMARY KEY (id_verif_keg);
 C   ALTER TABLE ONLY manajemen.verif_keg DROP CONSTRAINT ver_keg_pkey;
    	   manajemen         postgres    false    206            A      x?????? ? ?      B      x?????? ? ?      C      x?????? ? ?      J      x?????? ? ?      L      x?????? ? ?      D      x?????? ? ?      G      x?????? ? ?      F      x?????? ? ?      E      x?????? ? ?      H      x?????? ? ?      K      x?????? ? ?      I      x?????? ? ?     