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
                                            <th>Pembimbing</th>
                                            <th>Nama Pembimbing</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
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
                ajax: '/mahasiswa/get-jadwalbimbingan',
                columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'pembimbing',
                    className: 'text-center',
                }, {
                    data: 'nama',
                    className: 'text-center',
                }, {
                    data: 'tanggal',
                    className: 'text-center',
                }, {
                    data: 'waktu',
                    className: 'text-center',
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

    <script>
        document.addEventListener('DOMContentLoaded', function() { // Menangani koneksi status
            const studentUuid = "{{ auth()->user()->uuid }}";

            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                window.Echo.connector.pusher.connection.bind('state_change', states => {

                    if (states.current === 'connected') {
                        console.log('Pusher connected');
                    } else if (states.current === 'disconnected') {
                        console.log('Pusher disconnected');
                    }
                });

                window.Echo.channel(`notifications.${studentUuid}`)
                    .listen('.jadwal-dibuat', (event) => {
                        swal.fire({
                            title: event.message,
                            text: event.tanggal + event.waktu,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    });
            } else {
                console.error('Echo atau Echo.connector tidak diinisialisasi dengan benar.');
            }
        });
    </script>
@endsection
