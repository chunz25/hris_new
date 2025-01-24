<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class M_app extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function record_count()
  {
    // Count uploads
    try {
      return $this->db->count_all("pegawai");
    } catch (Exception $e) {
      log_message('error', 'Failed to count records: ' . $e->getMessage());
      return 0; // Return 0 on error
    }
  }

  public function errcount()
  { //menghitung jumlah error upload
    return $this->db->count_all("report.errmsg");
  }

  public function getData($limit, $start)
  {
    // Query upload report data
    try {
      $query = $this->db->query("
                SELECT * 
                FROM report.vw_reportupload
                ORDER BY pegawaiid DESC
                OFFSET ? ROWS
                FETCH NEXT ? ROWS ONLY
            ", array($start, $limit));

      return $query->num_rows() > 0 ? $query->result() : false;
    } catch (Exception $e) {
      log_message('error', 'Failed to retrieve data: ' . $e->getMessage());
      return false;
    }
  }

  public function getModuleList($userId)
  {
    // Get module list for user
    try {
      $query = $this->db->query("
                SELECT
                  a.id as modulid , 
                  a.name as modul , 
                  a.deskripsi as moduldesc , 
                  a.icon as iconid
                FROM modul a
                CROSS JOIN users b
                WHERE b.id = ?
                  AND CASE WHEN b.nik IN ('1003049', '16030068', '13121215', '15080236') THEN a.id IN (1, 2, 4, 5) 
                        ELSE a.id IN (2, 4, 5) 
                      END
                ORDER BY 
                  CASE 
                      WHEN a.id = 5 THEN 1
                      WHEN a.id = 1 THEN 5
                      ELSE a.id 
                  END ASC
            ", array($userId));

      return ['success' => true, 'data' => $query->result_array()];
    } catch (Exception $e) {
      log_message('error', 'Failed to retrieve module list: ' . $e->getMessage());
      return ['success' => false, 'error' => 'Failed to retrieve module list'];
    }
  }

  public function errdata($limit, $start)
  {
    // Display upload error messages
    try {
      $query = $this->db->query("
                SELECT CONCAT(field, row) AS nama_kolom, msg AS error_message
                FROM report.errmsg
                ORDER BY row, field
                OFFSET ? ROWS
                FETCH NEXT ? ROWS ONLY
            ", array($start, $limit));

      return $query->num_rows() > 0 ? $query->result() : false;
    } catch (Exception $e) {
      log_message('error', 'Failed to retrieve error data: ' . $e->getMessage());
      return false;
    }
  }

  function update($pegawaiid)
  {//update change password
    $data = array(
      'password' => MD5($this->input->post('passconf')),
    );
    // die(var_dump($data));
    $this->db->where('id', $pegawaiid);
    $this->db->update('users', $data);
    return true;
  }

  public function isOldPassword($oldpassword)
  {
    // Validate old password
    $this->db->select('id');
    $this->db->where('password', MD5($oldpassword));
    $query = $this->db->get('users');

    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  function tambahPegawai($params)
  { //upload data ke staging data pegawai
    $this->load->database();
    $this->db->trans_start();
    $this->db->query("
		INSERT into staging.stgdatapegawai VALUES (
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
			(SELECT COALESCE(MAX(CAST(id AS INT)),0) FROM pegawai),
			(SELECT COALESCE(MAX(id),0) FROM users),
			(SELECT count(*)+1 FROM staging.stgdatapegawai)
			);
		", $params);
    $this->db->trans_complete();
    $this->db->close();
    return $this->db->trans_status();
  }

  function uploadUsers()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Use parameterized query for security and readability
      $this->db->query("
            INSERT INTO users (id, nik, password)
            SELECT
              (CAST(v_iduser AS INT) + v_count) AS id,
              v_nik AS nik,
              ? AS password
            FROM staging.stgdatapegawai
        ", array('0fc536194ebc3275ae21ff1c612f3c1b'));

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Users data.');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadUsers Error: ' . $e->getMessage());
      return false;
    } finally {
      // Close the database connection
      $this->db->close();
    }
  }

  function uploadPegawai()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Use a parameterized query for the `isaktif` value
      $this->db->query("
            INSERT INTO pegawai (id, userid, isaktif)
            SELECT 
                (CAST(v_idpegawai AS INT) + v_count) AS id,
                (CAST(v_iduser AS INT) + v_count) AS userid,
                ? AS isaktif
            FROM staging.stgdatapegawai
        ", array(1));

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Pegawai data.');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of an error
      $this->db->trans_rollback();
      log_message('error', 'uploadPegawai Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadDataPegawai()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO datapegawai (
              pegawaiid, tempatlahir, tgllahir, jeniskelamin, alamatktp, alamatdom, 
              goldarahid, agamaid, notelp, nohp, emailpribadi, emailkantor, noktp, 
              npwp, bpjskes, bpjsnaker, askes, paspor, nokk, statusnikahid, tglnikah, 
              bb, tb, kontakdaruratname, kontakdaruratno, kontakdaruratrelasi, hobi, 
              shioid, sizebaju, sizecelana, sizesepatu, sizerompi, ext
          )
          SELECT 
              (CAST(v_idpegawai AS INT) + v_count) AS pegawaiid,
              v_tempatlahir, TO_DATE(v_tgllahir,'DD/MM/YYYY'), v_jeniskelamin, v_alamatktp, v_alamat AS alamatdom,
              (SELECT id FROM goldarah 
                  WHERE upper(name) = upper(v_goldarah) 
                  AND rhesus = CASE 
                      WHEN UPPER(v_rhesus) LIKE 'POS%' OR v_rhesus LIKE '%+%' THEN 'Rh+' 
                      WHEN UPPER(v_rhesus) LIKE 'NEG%' OR v_rhesus LIKE '%-%' THEN 'Rh-' 
                      ELSE 'N/A' 
                  END) AS goldarahid,
              (SELECT id FROM agama WHERE upper(name) = upper(v_agamaid)) AS agamaid,
              v_telp AS notelp, v_hp AS nohp, v_email AS emailpribadi, v_emailkantor, v_noktp, v_npwp, v_bpjskes,
              v_jamsostek AS bpjsnaker, v_askes, v_paspor, v_nokk,
              (SELECT id FROM statusnikah WHERE upper(name) = upper(v_statusnikah)) AS statusnikahid,
              TO_DATE(v_tglnikah,'DD/MM/YYYY'), CAST(v_beratbadan AS INT) AS bb, CAST(v_tinggibadan AS INT) AS tb,
              v_namakontakdarurat AS kontakdaruratname, v_telpkontakdarurat AS kontakdaruratno, 
              v_relasikontakdarurat AS kontakdaruratrelasi, v_hobby AS hobi,
              (SELECT id FROM shio 
                  WHERE upper(namashio) = upper(v_shio) 
                  AND upper(unsurshio) = upper(v_unsur)) AS shioid,
              v_sizebaju, v_sizecelana, v_sizesepatu, v_sizerompi, v_noext AS ext
          FROM staging.stgdatapegawai
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Data Pegawai.');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadDataPegawai Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadNamaPegawai()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO namapegawai (pegawaiid, fullname, frontname, midname, endname)
            SELECT 
                CAST(v_idpegawai AS INT) + v_count, 
                v_fullnama,  
                v_namadepan, 
                v_namatengah, 
                v_namabelakang 
            FROM staging.stgdatapegawai;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Nama Pegawai.');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadNamaPegawai Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatJabatan()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatjabatan (pegawaiid, statuspegawaiid, tglmulai, tglselesai, lokasiid, tglakhirkontrak, tglpermanent)
            SELECT
                (CAST(v_idpegawai AS INT) + v_count),
                (SELECT id FROM statuspegawai WHERE name = v_statuspegawaiid),
                TO_DATE(v_tglmulai,'DD/MM/YYYY'),
                TO_DATE(v_tglselesai,'DD/MM/YYYY'),
                (SELECT id FROM lokasi WHERE code = v_kodelokasi),
                TO_DATE(v_tglakhirkontrak,'DD/MM/YYYY'),
                TO_DATE(v_tglpermanent,'DD/MM/YYYY')
            FROM staging.stgdatapegawai;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Jabatan .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatJabatan Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadSatkerPegawai()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO struktur.satkerpegawai (pegawaiid, satkerid, nourut, jabatanid, levelid)
        SELECT 
          (CAST(a.v_idpegawai AS INT) + a.v_count) , 
          b.id ,
          1 ,
          c.id ,
          d.id
        FROM staging.stgdatapegawai a
          LEFT JOIN struktur.vw_satkertree_hr b on UPPER(REPLACE(CONCAT(direktorat,divisi,departemen,seksi,subseksi),' ','')) = UPPER(replace(a.v_direktorat || a.v_divisi || a.v_departement || a.v_seksi || a.v_subseksi,' ',''))
          LEFT JOIN struktur.jabatan c on c.name = a.v_jabatanid
          LEFT JOIN struktur.levelgrade d on d.name = a.v_levelname;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Satker Pegawai.');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadSatkerPegawai Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatRekening()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatrekening (pegawaiid, nourut, nama, norek, namabank)
          SELECT 
            (CAST(v_idpegawai AS INT) + v_count) , 
            1,  
            v_namarek ,  
            v_norek , 
            v_namabank
          FROM staging.stgdatapegawai
          WHERE v_namarek is not null;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Rekening .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatRekening Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatPendidikan()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatpendidikan (pegawaiid, nourut, pendidikanid, jurusan, namainstitut, tahunkeluar, ipk)
          SELECT 
            CAST(v_idpegawai AS INT) + v_count ,
            1 as nourut , 
            (SELECT id FROM pendidikan WHERE upper(code) = upper(v_pendidikan)) ,
            v_jurusan , 
            v_namasekolah , 
            cast(v_tahunkeluar as int) , 
            CAST(v_ipk as DECIMAL(4,2))
          FROM staging.stgdatapegawai;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Pendidikan .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatPendidikan Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatKeluarga()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatkeluarga (pegawaiid, nourut, relasiid, nama, jeniskelamin, tgllahir, pendidikan, pekerjaan, tempatlahir, alamat)
          SELECT 
            pegawaiid , 
            cast(nourut as int) , 
            relasi , 
            nama , 
            jeniskelamin , 
            to_date(v_tgllahirpasangan,'DD/MM/YYYY') , 
            v_pendpasangan , 
            v_kerjapasangan , 
            v_tmptlahirpasangan , 
            v_alamatpasangan
          FROM staging.vw_stgriwayatkeluarga;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Keluarga .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatKeluarga Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatExp()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatexp (pegawaiid, nourut, perusahaan, jabatan, jobdesc, tahunmasuk, tahunkeluar, alasankeluar, atasan)
          SELECT 
              pegawaiid, 
              nourut, 
              kantorkerja, 
              jabkerja, 
              deskkerja, 
              masukkerja,   
              keluarkerja,  
              alasankerja, 
              atasankerja
          FROM staging.vw_stgriwayatexp;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Exp .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatExp Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatBahasa()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatbahasa (pegawaiid, nourut, bahasa, tertulis, lisan, baca)
          SELECT
            pegawaiid ,
            nourut ,
            bahasa ,
            tulis ,
            lisan ,
            baca
          FROM staging.vw_stgriwayatbahasa;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Bahasa .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatBahasa Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatSkill()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatskills (pegawaiid, nourut, keahlian, keterangan)
          SELECT 
            CAST(v_idpegawai AS INT) + v_count pegawaiid, 
            1 nourut, 
            v_keahlian, 
            v_keahliandesk
          FROM staging.stgdatapegawai
          where v_keahlian is not null;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Skill .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatSkill Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatKursus()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatkursus (pegawaiid, nourut, jeniskursus, dibiayai, tahun, lama, deskripsi)
          SELECT 
            CAST(v_idpegawai AS INT) + v_count as pegawaiid , 
            1 nourut , 
            v_training , 
            v_trainer , 
            CAST(v_periodetraining AS INT) , 
            v_durasi , 
            v_jenistraining
          FROM staging.stgdatapegawai
          WHERE v_training is not null;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Kursus .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatKursus Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function uploadRiwayatPenyakit()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          INSERT INTO riwayatpenyakit (pegawaiid, nourut, jenispenyakit, namapenyakit)
          SELECT
            pegawaiid ,
            nourut ,
            jenis ,
            v_penyakit
          FROM staging.vw_stgriwayatpenyakit;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Penyakit .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatPenyakit Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function deleteStaging()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          TRUNCATE TABLE staging.stgdatapegawai; 
          TRUNCATE TABLE report.errmsg;
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Penyakit .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatPenyakit Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }

  function resetUpload()
  {
    // Ensure the database is loaded
    $this->load->database();

    try {
      // Start the transaction
      $this->db->trans_start();

      // Parameterized query to prevent SQL injection and improve readability
      $this->db->query("
          DELETE FROM users WHERE id = (SELECT CAST(v_iduser AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM pegawai WHERE id = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM datapegawai WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM namapegawai WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatjabatan WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM struktur.satkerpegawai WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatrekening WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatpendidikan WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatkeluarga WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatexp WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatbahasa WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatskills WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatkursus WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
          DELETE FROM riwayatpenyakit WHERE pegawaiid = (SELECT CAST(v_idpegawai AS INT) + v_count FROM staging.stgdatapegawai);
        ");

      // Complete the transaction
      $this->db->trans_complete();

      // Check if the transaction was successful
      if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to upload Riwayat Penyakit .');
      }

      return true; // Successful transaction

    } catch (Exception $e) {
      // Rollback the transaction in case of error
      $this->db->trans_rollback();
      log_message('error', 'uploadRiwayatPenyakit Error: ' . $e->getMessage());
      return false;
    } finally {
      // Ensure the database connection is closed
      $this->db->close();
    }
  }
}
