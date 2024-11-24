@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">

                <div class="card">
                    <div class="card-body p-0">
                        <div class="container">
                            <div class="py-5 table-responsive">
                                <table id="kt_table_data"
                                    class="table table-striped table-rounded border border-gray-300 table-row-bordered table-row-gray-300">
                                    <thead class="text-center">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <th>No</th>
                                            <th>Angkatan</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('script')
    <script>
        let control = new Control();

        $(document).on('click', '.button-update', function(e) {
            e.preventDefault();

            const uuid = $(this).attr('data-uuid');

            $.ajax({
                    url: '/dosen/update-naskah/' + uuid,
                    method: 'GET',
                })
                .done(function(res) {
                    if (res.success) {
                        // Menginisialisasi ulang DataTable setelah update berhasil
                        initDatatable();

                        // Membuka file di tab baru
                        const fileUrl =
                            `/naskah/${res.data.file}`; // Sesuaikan path ini sesuai dengan struktur file
                        window.open(fileUrl, '_blank'); // Buka file di tab baru
                    } else {
                        console.error('Gagal mengambil data:', res.message);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Gagal melakukan permintaan AJAX:', textStatus, errorThrown);
                });
        });

        $(document).on('keyup', '#search_', function(e) {
            e.preventDefault();
            control.searchTable(this.value);
        })

        const initDatatable = async () => {
            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#kt_table_data')) {
                $('#kt_table_data').DataTable().clear().destroy();
            }

            // Initialize DataTable
            $('#kt_table_data').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                processing: true,
                ajax: '/dosen/get-naskah',
                columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'angkatan',
                    className: 'text-center',
                }, {
                    data: 'mahasiswa',
                    className: 'text-center',
                }, {
                    data: 'nim',
                    className: 'text-center',
                }, {
                    data: 'judul',
                    className: 'text-center',
                }, {
                    data: 'deskripsi',
                    className: 'text-center',
                }, {
                    data: 'status',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        var status = "";

                        if (data == 'Dibaca') {
                            status = `
                             <div class="d-flex justify-content-center align-items-center btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success p-2 py-1 text-success" style="gap: 5px;">
                                ${data}
                            </div>
                        `;
                        } else {
                            status = `
                             <div class="d-flex justify-content-center align-items-center btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-2 py-1" style="gap: 5px; color: red;">
                                ${data}
                            </div>
                        `;
                        }
                        return status;
                    }
                }, {
                    data: 'uuid',
                }],
                columnDefs: [{
                    targets: -1,
                    title: 'Aksi',
                    className: 'text-center',
                    width: '8rem',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `
                            <a href="" data-uuid="${data}" class="btn btn-primary button-update btn-sm p-2">
                               Check Naskah
                            </a>
                        `;
                    },
                }],
                rowCallback: function(row, data, index) {
                    var api = this.api();
                    var startIndex = api.context[0]._iDisplayStart;
                    var rowIndex = startIndex + index + 1;
                    $('td', row).eq(0).html(rowIndex);
                },
            });
        };

        $(function() {
            initDatatable();
        });
    </script>
@endsection
