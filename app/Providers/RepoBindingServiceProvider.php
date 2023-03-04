<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoBindingServiceProvider extends ServiceProvider
{
	public function register()
	{
		$app = $this->app;

        /*
        |--------------------------------------------------------------------------
        | Pengaturan
        |--------------------------------------------------------------------------
        */
		$app->bind('\App\Repositories\Contracts\Pengaturan\MenuInterface', function () {
			$repository = new \App\Repositories\Pengaturan\MenuRepository(new \App\Models\Pengaturan\Menu);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\LogActivityInterface', function () {
			$repository = new \App\Repositories\Pengaturan\LogActivityRepository(new \App\Models\Pengaturan\LogActivity);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\AktivitasLogInterface', function () {
			$repository = new \App\Repositories\Pengaturan\AktivitasLogRepository(new \App\Models\Pengaturan\AktivitasLog);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\MenuPenggunaInterface', function () {
			$repository = new \App\Repositories\Pengaturan\MenuPenggunaRepository(new \App\Models\Pengaturan\MenuPengguna);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\RoutesUserInterface', function () {
			$repository = new \App\Repositories\Pengaturan\RoutesUserRepository(new \App\Models\Pengaturan\RoutesUser);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\RoutesInterface', function () {
			$repository = new \App\Repositories\Pengaturan\RoutesRepository(new \App\Models\Pengaturan\Routes);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\ViewRoutesUserInterface', function () {
			$repository = new \App\Repositories\Pengaturan\ViewRoutesUserRepository(new \App\Models\Pengaturan\ViewRoutesUser);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\PreferensiPerusahaanInterface', function () {
			$repository = new \App\Repositories\Pengaturan\PreferensiPerusahaanRepository(new \App\Models\Pengaturan\PreferensiPerusahaan);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\PenomoranInterface', function () {
			$repository = new \App\Repositories\Pengaturan\PenomoranRepository(new \App\Models\Pengaturan\Penomoran);
			return $repository;
		});

		/*
		|--------------------------------------------------------------------------
		| Pengguna
		|--------------------------------------------------------------------------
		*/
		$app->bind('\App\Repositories\Contracts\Pengguna\AkunInterface', function () {
			$repository = new \App\Repositories\Pengguna\PenggunaRepository(new \App\Models\Pengguna\Pengguna);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengguna\MenuPenggunaInterface', function () {
			$repository = new \App\Repositories\Pengguna\MenuPenggunaRepository(new \App\Models\Pengguna\MenuPengguna);
			return $repository;
		});

		/*
		|--------------------------------------------------------------------------
		| Perusahaan
		|--------------------------------------------------------------------------
		*/

		$app->bind('\App\Repositories\Contracts\Perusahaan\DepartemenInterface', function () {
			$repository = new \App\Repositories\Perusahaan\DepartemenRepository(new \App\Models\Perusahaan\Departemen);
			return $repository;
		});


		/*
		|-------------------------------------------------------------------------------------------------------------------------------------
		| Pembelian
		|-------------------------------------------------------------------------------------------------------------------------------------
		*/

		$app->bind('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface', function () {
			$repository = new \App\Repositories\Pembelian\PermintaanPembelianHeaderRepository(new \App\Models\Pembelian\PermintaanPembelianHeader);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface', function () {
			$repository = new \App\Repositories\Pembelian\PermintaanPembelianDetailRepository(new \App\Models\Pembelian\PermintaanPembelianDetail);
			return $repository;
		});

        $app->bind('\App\Repositories\Contracts\Litbang\KelitbanganInterface', function () {
            $repository = new \App\Repositories\Litbang\KelitbanganRepository(new \App\Models\Litbang\Kelitbangan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\BidangKelitbanganInterface', function () {
            $repository = new \App\Repositories\Litbang\BidangKelitbanganRepository(new \App\Models\Litbang\BidangKelitbangan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\InovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\InovasiRepository(new \App\Models\Litbang\Inovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PelaksanaKelitbanganInterface', function () {
            $repository = new \App\Repositories\Litbang\PelaksanaKelitbanganRepository(new \App\Models\Litbang\PelaksanaKelitbangan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PelaksanaInovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\PelaksanaInovasiRepository(new \App\Models\Litbang\PelaksanaInovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\InstansiInterface', function () {
            $repository = new \App\Repositories\Litbang\InstansiRepository(new \App\Models\Litbang\Instansi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AgendaInterface', function () {
            $repository = new \App\Repositories\Litbang\AgendaRepository(new \App\Models\Litbang\Agenda);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\BeritaInterface', function () {
            $repository = new \App\Repositories\Litbang\BeritaRepository(new \App\Models\Litbang\Berita);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\UsulanPenelitianInterface', function () {
            $repository = new \App\Repositories\Litbang\UsulanPenelitianRepository(new \App\Models\Litbang\UsulanPenelitian);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\UsulanInovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\UsulanInovasiRepository(new \App\Models\Litbang\UsulanInovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AttachmentInterface', function () {
            $repository = new \App\Repositories\Litbang\AttachmentRepository(new \App\Models\Litbang\Attachment);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Pengguna\PenggunaInterface', function () {
            $repository = new \App\Repositories\Pengguna\PenggunaRepository(new \App\Models\Pengguna\Pengguna);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PenomoranInterface', function () {
            $repository = new \App\Repositories\Litbang\PenomoranRepository(new \App\Models\Litbang\Penomoran );
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\SuratKeluarInterface', function () {
            $repository = new \App\Repositories\Litbang\SuratKeluarRepository(new \App\Models\Litbang\SuratKeluar );
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\SuratMasukInterface', function () {
            $repository = new \App\Repositories\Litbang\SuratMasukRepository(new \App\Models\Litbang\SuratMasuk );
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\RegulasiInterface', function () {
            $repository = new \App\Repositories\Litbang\RegulasiRepository(new \App\Models\Litbang\Regulasi );
            return $repository;
        });


        $app->bind('\App\Repositories\Contracts\Litbang\SurveyInterface', function () {
            $repository = new \App\Repositories\Litbang\SurveyRepository(new \App\Models\Litbang\Survey );
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AuthPelaporanInterface', function () {
            $repository = new \App\Repositories\Litbang\AuthPelaporanRepository(new \App\Models\Litbang\AuthPelaporan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AksesAndroidInterface', function () {
            $repository = new \App\Repositories\Litbang\AksesAndroidRepository(new \App\Models\Litbang\AksesAndroid);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\LaporanInovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\LaporanInovasiRepository(new \App\Models\Litbang\LaporanInovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\LaporanPenelitianInterface', function () {
            $repository = new \App\Repositories\Litbang\LaporanPenelitianRepository(new \App\Models\Litbang\LaporanPenelitian);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\KomentarInterface', function () {
            $repository = new \App\Repositories\Litbang\KomentarRepository(new \App\Models\Litbang\Komentar);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\BalasanKomentarInterface', function () {
            $repository = new \App\Repositories\Litbang\BalasanKomentarRepository(new \App\Models\Litbang\BalasanKomentar);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PrefsetInterface', function () {
            $repository = new \App\Repositories\Litbang\PrefsetRepository(new \App\Models\Litbang\Prefset);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\JenisSuratInterface', function () {
            $repository = new \App\Repositories\Litbang\JenisSuratRepository(new \App\Models\Litbang\JenisSurat);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\SuratRekomendasiInterface', function () {
            $repository = new \App\Repositories\Litbang\SuratRekomendasiRepository(new \App\Models\Litbang\SuratRekomendasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\LayananIncubatorInterface', function () {
            $repository = new \App\Repositories\Litbang\LayananIncubatorRepository(new \App\Models\Litbang\LayananIncubator);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\JenisLayananIncubatorInterface', function () {
            $repository = new \App\Repositories\Litbang\JenisLayananIncubatorRepository(new \App\Models\Litbang\JenisLayananIncubator);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\InformasiPublikInterface', function () {
            $repository = new \App\Repositories\Litbang\InformasiPublikRepository(new \App\Models\Litbang\InformasiPublik);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\KategoriInformasiPublikInterface', function () {
            $repository = new \App\Repositories\Litbang\KategoriInformasiPublikRepository(new \App\Models\Litbang\KategoriInformasiPublik);
            return $repository;
        });


        $app->bind('\App\Repositories\Contracts\Litbang\ProfilInterface', function () {
            $repository = new \App\Repositories\Litbang\ProfilRepository(new \App\Models\Litbang\Profil);
            return $repository;
        });


	}
}
