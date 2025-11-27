@php
    $img = request()->input('view')
        ? 'http://lleida.test/admin/storage/lleida/person/5ea3e22bd290b479f872f4f1f8fa34f0.gif'
        : storage_path('app/lleida/person/5ea3e22bd290b479f872f4f1f8fa34f0.gif');
@endphp

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Targeta Identificació</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            /* border-collapse: collapse; */
            /* table-layout: fixed; */
        }

        td,
        th {
            padding: 4px;
            /* vertical-align: top; */
            border: 1px solid #000;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
        }

        .label {
            font-weight: bold;
        }

        .photo {
            text-align: center;
            font-size: 9pt;
        }

        .photo-person {
            width: 120px;
            /* height: 80px; */
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .title {
            font-size: 1.5em;
            display: inline-block;
            font-weight: bold;
        }

        /* Outer TD Styles */
        .outer-td {
            /* vertical-align: middle; Removed from here, moved to nested table's parent TD */
            line-height: normal;
            padding: 0;
            /* Removing padding from the outer TD to give the nested table full control */
        }

        /* Nested Table Styles */
        .nested-table {
            width: 100%;
            border-collapse: collapse;
            height: 50px;
            /* Defined height for vertical centering reference */
            /* Remove borders */
            border: none;
        }

        .nested-table tr {
            border: none;
        }

        /* Nested Table Cell Styles */
        .nested-table td {
            vertical-align: middle;
            /* Crucial: Centers content vertically in each column */
            line-height: normal;
            /* Ensure consistent line height */
            /* Remove borders */
            border: none;
        }

        .nested-table .text-cell {
            text-align: left;
            /* Aligns text to the left */
            width: 60%;
            /* Adjust width for text content */
        }

        .nested-table .image-cell {
            text-align: right;
            /* Aligns images to the right */
            width: 20%;
            /* Adjust width for image content */
            /* Add internal padding to image cells for spacing */
            padding-left: 5px;
            /* Adjust as needed */
            padding-right: 5px;
            /* Adjust as needed */
        }

        /* Image Specific Styles */
        .image-container {
            display: inline-block;
        }

        .image-container img {
            height: 50px;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="4" class="outer-td">
                <table class="nested-table">
                    <tr>
                        <td class="text-cell">
                            <span class="title">TARGETA IDENTIFICACIÓ</span>
                        </td>

                        <td class="image-cell">
                            <span class="image-container">
                                <img src="{{ public_path('/images/logos/lleida/lleida-logo.jpg') }}"
                                    style="height: 50px; display: inline-block; vertical-align: middle;">
                            </span>
                        </td>

                        <td class="image-cell">
                            <span class="image-container">
                                <img src="{{ public_path('/images/logos/lleida/mercats-lleida-logo.jpg') }}"
                                    style="height: 50px; display: inline-block; vertical-align: middle;">
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td rowspan="3" class="photo-person">
                @if ($person->image && \Storage::exists($person->image))
                    <img src="{{ storage_path('app/' . $person->image) }}"
                        style="
                        max-width: 100%;
                        max-height: 100%;
                        " />
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">ANY:</td>
            <td>{{ \Carbon\Carbon::now()->format('Y') }}</td>
            <td class="label">NÚM. LLICÈNCIA:</td>
            <td>{{ $person->license_number }}</td>
        </tr>
        <tr>
            <td class="label">TITULAR:</td>
            <td>{{ $person->name }}</td>
            <td class="label">NIF:</td>
            <td>{{ $person->dni_code }}</td>
        </tr>
        @if ($person->substitute1_name || $person->substitute2_name)
            <tr>
                <td colspan="5">
                    ACOMPANYANTS
                    <table>
                        <tr>
                            <th width="40%">Nom</th>
                            <th width="20%">FOTO</th>
                            <th width="40%">Nom</th>
                            <th width="20%">FOTO</th>
                        </tr>
                        <tr>
                            @if ($person->substitute1_name)
                                <td>{{ $person->substitute1_name }}<br>
                                    <small>({{ $person->obfuscateDni($person->substitute1_dni) }})</small>
                                </td>
                                <td class="photo">
                                    @if ($person->substitute1_img && \Storage::exists($person->substitute1_img))
                                        <img src="{{ storage_path('app/' . $person->substitute1_img) }}"
                                            style="max-height: 50px; max-width: 100%;">
                                    @endif
                                </td>
                            @endif

                            @if ($person->substitute2_name)
                                <td>{{ $person->substitute2_name }}<br>
                                    <small>({{ $person->obfuscateDni($person->substitute2_dni) }})</small>
                                </td>
                                <td class="photo">
                                    @if ($person->substitute2_img && \Storage::exists($person->substitute2_img))
                                        <img src="{{ storage_path('app/' . $person->substitute2_img) }}"
                                            style="max-height: 50px; max-width: 100%;">
                                    @endif
                                </td>
                            @endif
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="5">PARADES</td>
        </tr>
        @foreach ($stallsByMarket as $key => $stalls)
            <tr>
                <th style="text-align: left" colspan="3">{{ $stalls->first()->market->name ?? '' }}</th>
                <th>M²/ML/Quadres</th>
                <th>PRODUCTES</th>
            </tr>
            @foreach ($stalls as $stall)
                <tr>
                    <td colspan="3">{{ $stall->num }} ({{ $stall->marketGroup->title ?? '' }})</td>
                    <td>{{ $stall->length }}</td>
                    <td>{{ $stall->authProdsList() }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</body>

</html>
