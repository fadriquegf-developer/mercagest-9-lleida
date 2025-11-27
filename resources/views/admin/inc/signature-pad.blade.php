@if (isset($signature))
    <img src="{{ $signature }}" alt="signature">
@else
    <input type="hidden" id="signature" name="signature">
    <div class="wrapper text-center">
        <canvas id="signature-pad" class="signature-pad" style="border: 2px solid black;border-radius: 16px;" width=400
            height=200></canvas>
    </div>
    <div class="buttons-signature-pad">
        <button id="clear-signature-pad" type="button" class="btn btn-outline-primary px-4">Borrar</button>
        <button id="save-signature-pad" type="submit" class="btn btn-primary px-4">Firmar</button>
    </div>
    </form>
@endif

<script src="{{ asset('js/signature.js') }}"></script>
