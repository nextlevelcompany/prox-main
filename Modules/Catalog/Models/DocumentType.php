<?php

namespace Modules\Catalog\Models;

use Modules\Dispatch\Models\Dispatch;
use Modules\Document\Models\Document;
use Modules\Establishment\Models\SeriesConfiguration;
use Modules\Perception\Models\Perception;
use Modules\Perception\Models\PerceptionDocument;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseSettlement;
use Modules\Retention\Models\Retention;
use Modules\Retention\Models\RetentionDocument;
use Modules\SaleNote\Models\SaleNote;
use Modules\Establishment\Models\Series;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Purchase\Models\FixedAssetPurchase;

class DocumentType extends ModelCatalog
{
    public const SALE_DOCUMENT_TYPES = ['01', '03', '80'];

    public const CREDIT_NOTE_ID = '07';

    public const SALE_NOTE_ID = '80';

    public $incrementing = false;
    public $timestamps = false;
    protected $table = "cat_document_types";
    protected $fillable = [
        'id',
        'active',
        'short',
        'description'
    ];

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     *
     * @return DocumentType
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param mixed $short
     *
     * @return DocumentType
     */
    public function setShort($short)
    {
        $this->short = $short;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return DocumentType
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Builder
     */
    public function scopeOnlyActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * @return Builder
     */
    public function scopeOnlyAvaibleDocuments($query)
    {
        return $query->OnlyActive()->wherein('id', ['01', '03', '07', '08', '09', '20', '40',
            '80', '04', 'U2', 'U3', 'U4', '31', 'U5', 'U6', 'U7']);
    }

    /**
     * Devuelve los elementos activos para compras
     *
     * @return Builder
     */
    public function scopeDocumentsActiveToPurchase($query)
    {
        return $query->OnlyActive()->wherein('id', ['01', '02', '03', 'GU75', 'NE76', '14', '07', '08']);
    }

    public function scopeDocumentsActiveToSettlement($query)
    {
        return $query->OnlyActive()->wherein('id', ['04']);
    }

    public function dispatches_where_document_type(): HasMany
    {
        return $this->hasMany(Dispatch::class, 'document_type_id', 'id');
    }

     public function documents_where_document_type(): HasMany
    {
        return $this->hasMany(Document::class, 'document_type_id', 'id');
    }

    public function fixed_asset_purchases_where_document_type(): HasMany
    {
        return $this->hasMany(FixedAssetPurchase::class, 'document_type_id', 'id');
    }

    public function perception_documents_where_document_type(): HasMany
    {
        return $this->hasMany(PerceptionDocument::class, 'document_type_id', 'id');
    }

    public function perceptions_where_document_type(): HasMany
    {
        return $this->hasMany(Perception::class, 'document_type_id', 'id');
    }

    public function purchase_settlements_where_document_type(): HasMany
    {
        return $this->hasMany(PurchaseSettlement::class, 'document_type_id', 'id');
    }

    public function purchases_where_document_type(): HasMany
    {
        return $this->hasMany(Purchase::class, 'document_type_id', 'id');
    }

    public function retention_documents_where_document_type(): HasMany
    {
        return $this->hasMany(RetentionDocument::class, 'document_type_id', 'id');
    }

    public function retentions_where_document_type(): HasMany
    {
        return $this->hasMany(Retention::class, 'document_type_id', 'id');
    }

    public function series_where_document_type(): HasMany
    {
        return $this->hasMany(Series::class, 'document_type_id', 'id');
    }

    public function series_configurations_where_document_type(): HasMany
    {
        return $this->hasMany(SeriesConfiguration::class, 'document_type_id', 'id');
    }

    /**
     * Devuelve el nombre de la clase correspondiente al documento
     *
     * @return string
     */
    public function getCurrentRelatiomClass()
    {
        //09	1		GUIA DE REMISIÓN REMITENTE
        //20	1		COMPROBANTE DE RETENCIÓN ELECTRÓNICA
        //31	1		Guía de remisión transportista
        //40	1		COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA
        //71	0		Guia de remisión remitente complementaria
        //72	0		Guia de remisión transportista complementaria
        //GU75	1		GUÍA
        //NE76	1		NOTA DE ENTRADA
        //02	1		RECIBO POR HONORARIOS
        //14	1		SERVICIOS PÚBLICOS
        //04	1		LIQUIDACIÓN DE COMPRA

        //01	1	FT	FACTURA ELECTRÓNICA
        if ($this->id == '01') {
            return Document::class;
        } //03	1	BV	BOLETA DE VENTA ELECTRÓNICA
        elseif ($this->id == '03') {
            return Document::class;
        } //07	1	NC	NOTA DE CRÉDITO
        elseif ($this->id == '07') {
            return Document::class;
        } //08	1	ND	NOTA DE DÉBITO
        elseif ($this->id == '08') {
            return Document::class;
        }
        // elseif($this->id == '09'){ return Document::class;}
        // elseif($this->id == '20'){ return Document::class;}
        // elseif($this->id == '31'){ return Document::class;}
        // elseif($this->id == '40'){ return Document::class;}
        // elseif($this->id == '71'){ return Document::class;}
        // elseif($this->id == '72'){ return Document::class;}
        // elseif($this->id == 'GU75'){ return Document::class;}
        // elseif($this->id == 'NE76'){ return Document::class;}
        //80	1		NOTA DE VENTA
        elseif ($this->id == '80') {
            return SaleNote::class;
        }
        // elseif($this->id == '02'){ return Document::class;}
        // elseif($this->id == '14'){ return Document::class;}
        // elseif($this->id == '04'){ return Document::class;}
        else {
            return Document::class;
        }


    }


    /**
     * @return Builder
     */
    public function scopeOnlySaleDocuments($query)
    {
        return $query->onlyActive()->select('id', 'description')->whereIn('id', self::SALE_DOCUMENT_TYPES);
    }


    /**
     *
     * Filtro para la descripción
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterOnlyDescription($query)
    {
        return $query->select('id', 'description');
    }


    /**
     *
     * @return bool
     */
    public function isInvoice()
    {
        return in_array($this->id, self::INVOICE_DOCUMENTS_IDS, true);
    }

}
