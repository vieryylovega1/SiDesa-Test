<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\FamilyCard;
use App\Models\LetterRequest;
use App\Models\NewsCategory;
use App\Models\NewsPost;
use App\Models\Resident;
use App\Models\SocialAssistanceCategory;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_open_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function test_login_records_session_and_rejects_inactive_user(): void
    {
        $active = User::factory()->create([
            'email' => 'secure@sidesa.test',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'is_active' => true,
        ]);

        $inactive = User::factory()->create([
            'email' => 'inactive@sidesa.test',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'is_active' => false,
        ]);

        $this->post('/login', [
            'email' => $inactive->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => $active->email,
            'password' => 'password',
        ])->assertRedirect('/');

        $active->refresh();

        $this->assertNotNull($active->current_session_id);
        $this->assertNotNull($active->last_login_at);
        $this->assertSame(1, $active->login_count);
        $this->assertSame(0, $active->failed_login_attempts);
    }

    public function test_failed_login_locks_user_temporarily_after_repeated_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'locked@sidesa.test',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'password-salah',
            ])->assertSessionHasErrors('email');
        }

        $user->refresh();

        $this->assertSame(5, $user->failed_login_attempts);
        $this->assertNotNull($user->locked_until);
        $this->assertTrue($user->locked_until->isFuture());
    }

    public function test_old_session_is_rejected_when_account_logs_in_elsewhere(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
            'current_session_id' => 'newer-session-id',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_read_dashboard_statistics(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/dashboard/statistik');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'updated_at',
                'summary' => ['residents', 'male', 'female', 'families', 'rt', 'rw', 'neighborhoods'],
                'gender',
                'occupation',
                'education',
                'monthly',
            ]);
    }

    public function test_authenticated_user_can_open_agenda_with_good_and_attention_content(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get('/agenda')
            ->assertStatus(200)
            ->assertSee('Agenda Desa')
            ->assertSee('Kabar Baik')
            ->assertSee('Perlu Perhatian')
            ->assertSee('Posyandu Balita dan Lansia Terpadu')
            ->assertSee('Verifikasi Ulang Penerima Bantuan Sosial');
    }

    public function test_operator_can_filter_and_export_residents(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        Resident::create([
            'nik' => '3301010101010001',
            'kk' => '3301010101019999',
            'name' => 'Warga Uji',
            'gender' => 'Laki-laki',
            'birth_place' => 'Sukamaju',
            'birth_date' => '2000-01-01',
            'religion' => 'Islam',
            'occupation' => 'Petani',
            'education' => 'SMA/SMK',
            'marital_status' => 'Belum Kawin',
            'address' => 'Jl. Uji',
            'rt' => '001',
            'rw' => '002',
            'status' => 'Aktif',
        ]);

        $this->actingAs($user)
            ->get('/penduduk?gender=Laki-laki&education=SMA%2FSMK')
            ->assertStatus(200)
            ->assertSee('Warga Uji');

        $this->actingAs($user)
            ->get('/penduduk/export/excel?gender=Laki-laki')
            ->assertStatus(200)
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_operator_can_view_family_card_members(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $familyCard = FamilyCard::create([
            'number' => '3301010101018888',
            'head_name' => 'Kepala Uji',
            'address' => 'Jl. Keluarga',
            'rt' => '001',
            'rw' => '002',
        ]);

        Resident::create([
            'nik' => '3301010101010002',
            'kk' => $familyCard->number,
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Kepala Uji',
            'gender' => 'Laki-laki',
            'birth_place' => 'Sukamaju',
            'birth_date' => '1980-01-01',
            'religion' => 'Islam',
            'occupation' => 'Petani',
            'education' => 'SMA/SMK',
            'marital_status' => 'Kawin',
            'address' => 'Jl. Keluarga',
            'rt' => '001',
            'rw' => '002',
            'status' => 'Aktif',
        ]);

        $this->actingAs($user)
            ->get('/kartu-keluarga/' . $familyCard->id)
            ->assertStatus(200)
            ->assertSee('Kepala Uji')
            ->assertSee('Kepala Keluarga');
    }

    public function test_operator_can_generate_letter_pdf_and_public_verification(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $resident = Resident::create([
            'nik' => '3301010101010003',
            'kk' => '3301010101017777',
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Pemohon Surat',
            'gender' => 'Laki-laki',
            'birth_place' => 'Sukamaju',
            'birth_date' => '1990-01-01',
            'religion' => 'Islam',
            'occupation' => 'Pedagang',
            'education' => 'SMA/SMK',
            'marital_status' => 'Kawin',
            'address' => 'Jl. Surat',
            'rt' => '001',
            'rw' => '002',
            'status' => 'Aktif',
        ]);

        $this->actingAs($user)->post('/layanan-surat', [
            'resident_id' => $resident->id,
            'letter_code' => 'domisili',
            'purpose' => 'keperluan administrasi',
            'phone' => '081234567890',
            'status' => 'Diajukan',
            'requested_at' => now()->format('Y-m-d'),
        ])->assertRedirect('/layanan-surat');

        $letter = LetterRequest::where('resident_id', $resident->id)->firstOrFail();

        $this->actingAs($user)
            ->patch('/layanan-surat/' . $letter->id . '/setujui')
            ->assertRedirect();

        $letter->refresh();

        $this->actingAs($user)
            ->get('/layanan-surat/' . $letter->id . '/pdf')
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');

        $this->get('/verifikasi-surat/' . $letter->verification_code)
            ->assertStatus(200)
            ->assertSee('Surat Valid')
            ->assertSee($letter->letter_number);
    }

    public function test_news_can_be_published_and_commented(): void
    {
        $user = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $category = NewsCategory::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
        ]);

        $this->actingAs($user)->post('/berita', [
            'news_category_id' => $category->id,
            'title' => 'Kerja Bakti Desa',
            'excerpt' => 'Warga melaksanakan kerja bakti.',
            'content' => 'Kerja bakti dilakukan untuk menjaga kebersihan lingkungan desa.',
            'status' => 'published',
        ])->assertRedirect('/berita');

        $post = NewsPost::where('title', 'Kerja Bakti Desa')->firstOrFail();

        $this->get('/berita-desa/' . $post->slug)
            ->assertStatus(200)
            ->assertSee('Kerja Bakti Desa');

        $this->post('/berita-desa/' . $post->slug . '/komentar', [
            'name' => 'Warga',
            'email' => 'warga@example.com',
            'comment' => 'Informasi yang bermanfaat.',
        ])->assertRedirect();

        $this->assertDatabaseHas('news_comments', [
            'news_post_id' => $post->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_desa_can_manage_social_assistance_recipient_and_history(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_desa',
            'is_active' => true,
        ]);

        $resident = Resident::create([
            'nik' => '3301010101010004',
            'kk' => '3301010101016666',
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Penerima Bantuan',
            'gender' => 'Perempuan',
            'birth_place' => 'Sukamaju',
            'birth_date' => '1975-01-01',
            'religion' => 'Islam',
            'occupation' => 'Buruh',
            'education' => 'SD',
            'marital_status' => 'Kawin',
            'address' => 'Jl. Bantuan',
            'rt' => '004',
            'rw' => '005',
            'status' => 'Aktif',
        ]);

        $category = SocialAssistanceCategory::create([
            'name' => 'BLT Desa',
            'slug' => 'blt-desa',
            'description' => 'Bantuan langsung tunai desa.',
            'is_active' => true,
        ]);

        $this->actingAs($user)->post('/bantuan-sosial', [
            'resident_id' => $resident->id,
            'social_assistance_category_id' => $category->id,
            'status' => 'active',
            'registered_at' => now()->format('Y-m-d'),
            'eligibility_note' => 'Layak menerima bantuan berdasarkan validasi RT/RW.',
        ])->assertRedirect('/bantuan-sosial');

        $recipient = SocialAssistanceRecipient::where('resident_id', $resident->id)->firstOrFail();

        $this->assertDatabaseHas('social_assistance_recipients', [
            'resident_id' => $resident->id,
            'social_assistance_category_id' => $category->id,
            'status' => 'active',
        ]);

        $this->actingAs($user)->post('/bantuan-sosial', [
            'resident_id' => $resident->id,
            'social_assistance_category_id' => $category->id,
            'status' => 'active',
            'registered_at' => now()->format('Y-m-d'),
            'eligibility_note' => 'Duplikasi penerima pada kategori yang sama.',
        ])->assertSessionHasErrors('social_assistance_category_id');

        $this->actingAs($user)->post('/bantuan-sosial/' . $recipient->id . '/riwayat', [
            'distributed_at' => now()->format('Y-m-d'),
            'period' => 'Mei 2026',
            'amount' => 300000,
            'status' => 'disalurkan',
            'description' => 'Bantuan tahap pertama.',
        ])->assertRedirect();

        $this->assertDatabaseHas('social_assistance_histories', [
            'social_assistance_recipient_id' => $recipient->id,
            'period' => 'Mei 2026',
            'status' => 'disalurkan',
        ]);
    }

    public function test_operator_can_view_but_cannot_create_social_assistance(): void
    {
        $operator = User::factory()->create([
            'role' => 'operator',
            'is_active' => true,
        ]);

        $resident = Resident::create([
            'nik' => '3301010101010005',
            'kk' => '3301010101015555',
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Warga Bansos',
            'gender' => 'Laki-laki',
            'birth_place' => 'Sukamaju',
            'birth_date' => '1982-01-01',
            'religion' => 'Islam',
            'occupation' => 'Petani',
            'education' => 'SMP',
            'marital_status' => 'Kawin',
            'address' => 'Jl. Sawah',
            'rt' => '002',
            'rw' => '001',
            'status' => 'Aktif',
        ]);

        $category = SocialAssistanceCategory::create([
            'name' => 'PKH',
            'slug' => 'pkh',
            'description' => 'Program Keluarga Harapan.',
            'is_active' => true,
        ]);

        SocialAssistanceRecipient::create([
            'resident_id' => $resident->id,
            'social_assistance_category_id' => $category->id,
            'status' => 'active',
            'registered_at' => now(),
            'eligibility_note' => 'Validasi awal lengkap.',
            'created_by' => $operator->id,
        ]);

        $this->actingAs($operator)
            ->get('/bantuan-sosial')
            ->assertStatus(200)
            ->assertSee('Warga Bansos');

        $this->actingAs($operator)
            ->get('/bantuan-sosial/create')
            ->assertStatus(403);
    }

    public function test_warga_can_submit_and_view_own_complaint(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->actingAs($warga)->post('/pengaduan', [
            'reporter_name' => 'Warga Pelapor',
            'phone' => '081234567890',
            'address' => 'RT 001/RW 002',
            'category' => 'Infrastruktur',
            'title' => 'Jalan berlubang',
            'description' => 'Jalan utama berlubang dan berbahaya saat malam.',
        ])->assertRedirect();

        $complaint = Complaint::where('user_id', $warga->id)->firstOrFail();

        $this->assertDatabaseHas('complaints', [
            'user_id' => $warga->id,
            'title' => 'Jalan berlubang',
            'status' => 'baru',
        ]);

        $this->actingAs($warga)
            ->get('/pengaduan/' . $complaint->id)
            ->assertStatus(200)
            ->assertSee('Jalan berlubang')
            ->assertSee($complaint->ticket_number);
    }

    public function test_warga_cannot_view_other_user_complaint(): void
    {
        $owner = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $other = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $complaint = Complaint::create([
            'user_id' => $owner->id,
            'ticket_number' => 'LPR-20260522-ABCDE',
            'reporter_name' => 'Pemilik Laporan',
            'category' => 'Pelayanan',
            'title' => 'Laporan pribadi',
            'description' => 'Isi laporan pribadi warga.',
            'status' => 'baru',
        ]);

        $this->actingAs($other)
            ->get('/pengaduan/' . $complaint->id)
            ->assertStatus(403);
    }

    public function test_admin_can_reply_to_complaint(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_desa',
            'is_active' => true,
        ]);

        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $complaint = Complaint::create([
            'user_id' => $warga->id,
            'ticket_number' => 'LPR-20260522-FGHIJ',
            'reporter_name' => 'Warga Pelapor',
            'category' => 'Kebersihan',
            'title' => 'Sampah menumpuk',
            'description' => 'Sampah menumpuk di pinggir jalan desa.',
            'status' => 'baru',
        ]);

        $this->actingAs($admin)->patch('/pengaduan/' . $complaint->id . '/balasan', [
            'status' => 'diproses',
            'admin_reply' => 'Laporan sudah diterima dan akan ditindaklanjuti petugas.',
        ])->assertRedirect();

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'diproses',
            'admin_reply' => 'Laporan sudah diterima dan akan ditindaklanjuti petugas.',
            'replied_by' => $admin->id,
        ]);
    }

    public function test_custom_404_page_is_available(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertStatus(404)
            ->assertSee('Halaman tidak ditemukan');
    }

    public function test_global_search_returns_relevant_results(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_desa',
            'is_active' => true,
        ]);

        Resident::create([
            'nik' => '3301010101010099',
            'kk' => '3301010101011199',
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Budi Pencarian',
            'gender' => 'Laki-laki',
            'birth_place' => 'Sukamaju',
            'birth_date' => '1988-01-01',
            'religion' => 'Islam',
            'occupation' => 'Petani',
            'education' => 'SMA/SMK',
            'marital_status' => 'Kawin',
            'address' => 'Jl. Search',
            'rt' => '001',
            'rw' => '001',
            'status' => 'Aktif',
        ]);

        $this->actingAs($user)
            ->get('/pencarian?q=Budi')
            ->assertStatus(200)
            ->assertSee('Budi Pencarian');
    }

    public function test_kepala_desa_can_view_and_export_reports(): void
    {
        $user = User::factory()->create([
            'role' => 'kepala_desa',
            'is_active' => true,
        ]);

        Resident::create([
            'nik' => '3301010101010123',
            'kk' => '3301010101011123',
            'family_relationship' => 'Kepala Keluarga',
            'name' => 'Warga Laporan',
            'gender' => 'Perempuan',
            'birth_place' => 'Sukamaju',
            'birth_date' => now()->subYears(20)->format('Y-m-d'),
            'religion' => 'Islam',
            'occupation' => 'Guru',
            'education' => 'S1',
            'marital_status' => 'Belum Kawin',
            'address' => 'Jl. Laporan',
            'rt' => '009',
            'rw' => '008',
            'status' => 'Aktif',
        ]);

        $this->actingAs($user)
            ->get('/laporan?type=penduduk-rt-rw')
            ->assertStatus(200)
            ->assertSee('Laporan Penduduk per RT/RW');

        $this->actingAs($user)
            ->get('/laporan/export/excel?type=penduduk-rt-rw')
            ->assertStatus(200)
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_update_village_profile_and_audit_log_is_created(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_desa',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->put('/pengaturan/profil-desa', [
            'village_name' => 'Sukamaju Baru',
            'district' => 'Harmoni',
            'regency' => 'Sentosa',
            'province' => 'Jawa Tengah',
            'postal_code' => '55555',
            'address' => 'Jl. Raya Desa No. 1',
            'phone' => '0274000000',
            'email' => 'desa@example.com',
            'website' => 'https://desa.example.com',
            'head_name' => 'Kepala Desa Uji',
            'head_nip' => '196805101990031002',
        ])->assertRedirect();

        $this->assertDatabaseHas('village_profiles', [
            'village_name' => 'Sukamaju Baru',
            'head_name' => 'Kepala Desa Uji',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'updated',
            'description' => 'Memperbarui profil desa',
        ]);

        $this->actingAs($admin)
            ->get('/audit-log')
            ->assertStatus(200)
            ->assertSee('Memperbarui profil desa');
    }

    public function test_warga_can_open_citizen_portal(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->actingAs($warga)
            ->get('/portal-warga')
            ->assertStatus(200)
            ->assertSee('Portal Warga')
            ->assertSee('Kirim Pengaduan');
    }
}
