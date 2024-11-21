<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, doğrulama sınıfı tarafından kullanılan varsayılan
    | hata mesajlarını içerir. Bu kuralların bazıları birden fazla versiyona
    | sahiptir, örneğin boyut kuralları gibi. Buradaki mesajları değiştirebilirsiniz.
    |
    */

    'accepted'        => ':attribute kabul edilmelidir.',
    'accepted_if'     => ':attribute, :other :value olduğunda kabul edilmelidir.',
    'active_url'      => ':attribute geçerli bir URL olmalıdır.',
    'after'           => ':attribute, :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal'  => ':attribute, :date tarihine eşit veya sonraki bir tarih olmalıdır.',
    'alpha'           => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash'      => ':attribute sadece harf, rakam, tire ve alt çizgi içerebilir.',
    'alpha_num'       => ':attribute sadece harf ve rakam içerebilir.',
    'array'           => ':attribute bir dizi olmalıdır.',
    'before'          => ':attribute, :date tarihinden önceki bir tarih olmalıdır.',
    'before_or_equal' => ':attribute, :date tarihine eşit veya önceki bir tarih olmalıdır.',
    'between'         => [
        'array'   => ':attribute, :min ile :max arasında öğe içermelidir.',
        'file'    => ':attribute, :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute, :min ile :max arasında olmalıdır.',
        'string'  => ':attribute, :min ile :max karakter arasında olmalıdır.',
    ],
    'boolean'           => ':attribute alanı doğru veya yanlış olmalıdır.',
    'confirmed'         => ':attribute onayı eşleşmiyor.',
    'current_password'  => 'Parola yanlış.',
    'date'              => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals'       => ':attribute, :date tarihine eşit bir tarih olmalıdır.',
    'date_format'       => ':attribute, :format formatıyla eşleşmiyor.',
    'declined'          => ':attribute reddedilmelidir.',
    'declined_if'       => ':attribute, :other :value olduğunda reddedilmelidir.',
    'different'         => ':attribute ile :other farklı olmalıdır.',
    'digits'            => ':attribute, :digits basamak olmalıdır.',
    'digits_between'    => ':attribute, :min ile :max basamak arasında olmalıdır.',
    'dimensions'        => ':attribute geçersiz resim boyutlarına sahiptir.',
    'distinct'          => ':attribute alanında yinelenen bir değer var.',
    'doesnt_end_with'   => ':attribute, şu değerlerden biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute, şu değerlerden biriyle başlamamalıdır: :values.',
    'email'             => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with'         => ':attribute, şu değerlerden biriyle bitmelidir: :values.',
    'enum'              => 'Seçilen :attribute geçersiz.',
    'exists'            => 'Seçilen :attribute geçersiz.',
    'file'              => ':attribute bir dosya olmalıdır.',
    'filled'            => ':attribute alanında bir değer bulunmalıdır.',
    'gt'                => [
        'array'   => ':attribute, :value öğeden fazla öğe içermelidir.',
        'file'    => ':attribute, :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string'  => ':attribute, :value karakterden büyük olmalıdır.',
    ],
    'gte' => [
        'array'   => ':attribute, :value veya daha fazla öğe içermelidir.',
        'file'    => ':attribute, :value kilobayt veya daha büyük olmalıdır.',
        'numeric' => ':attribute, :value veya daha büyük olmalıdır.',
        'string'  => ':attribute, :value karakter veya daha fazla olmalıdır.',
    ],
    'image'    => ':attribute bir resim olmalıdır.',
    'in'       => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer'  => ':attribute bir tamsayı olmalıdır.',
    'ip'       => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4'     => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6'     => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json'     => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'lt'       => [
        'array'   => ':attribute, :value öğeden az öğe içermelidir.',
        'file'    => ':attribute, :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'string'  => ':attribute, :value karakterden küçük olmalıdır.',
    ],
    'lte' => [
        'array'   => ':attribute, :value öğeden fazla içermemelidir.',
        'file'    => ':attribute, :value kilobayt veya daha küçük olmalıdır.',
        'numeric' => ':attribute, :value veya daha küçük olmalıdır.',
        'string'  => ':attribute, :value karakter veya daha az olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max'         => [
        'array'   => ':attribute, :max öğeden fazla içermemelidir.',
        'file'    => ':attribute, :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute, :max değerinden büyük olmamalıdır.',
        'string'  => ':attribute, :max karakterden fazla olmamalıdır.',
    ],
    'max_digits' => ':attribute, :max basamaktan fazla olmamalıdır.',
    'mimes'      => ':attribute şu türde bir dosya olmalıdır: :values.',
    'mimetypes'  => ':attribute şu türde bir dosya olmalıdır: :values.',
    'min'        => [
        'array'   => ':attribute, en az :min öğe içermelidir.',
        'file'    => ':attribute, en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute, en az :min olmalıdır.',
        'string'  => ':attribute, en az :min karakter olmalıdır.',
    ],
    'min_digits'  => ':attribute, en az :min basamak olmalıdır.',
    'multiple_of' => ':attribute, :value katı olmalıdır.',
    'not_in'      => 'Seçilen :attribute geçersiz.',
    'not_regex'   => ':attribute formatı geçersiz.',
    'numeric'     => ':attribute bir sayı olmalıdır.',
    'password'    => [
        'letters'       => ':attribute en az bir harf içermelidir.',
        'mixed'         => ':attribute en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers'       => ':attribute en az bir rakam içermelidir.',
        'symbols'       => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında bulundu. Lütfen farklı bir :attribute seçin.',
    ],
    'present'              => ':attribute alanı mevcut olmalıdır.',
    'prohibited'           => ':attribute alanı yasaktır.',
    'prohibited_if'        => ':attribute alanı, :other :value olduğunda yasaktır.',
    'prohibited_unless'    => ':attribute alanı, :other :values içinde olmadıkça yasaktır.',
    'prohibits'            => ':attribute alanı, :other alanının mevcut olmasını yasaklar.',
    'regex'                => ':attribute formatı geçersiz.',
    'required'             => ':attribute alanı zorunludur.',
    'required_array_keys'  => ':attribute alanı şu girdileri içermelidir: :values.',
    'required_if'          => ':attribute alanı, :other :value olduğunda zorunludur.',
    'required_if_accepted' => ':attribute alanı, :other kabul edildiğinde zorunludur.',
    'required_unless'      => ':attribute alanı, :other :values içinde olmadıkça zorunludur.',
    'required_with'        => ':attribute alanı, :values mevcut olduğunda zorunludur.',
    'required_with_all'    => ':attribute alanı, :values mevcut olduğunda zorunludur.',
    'required_without'     => ':attribute alanı, :values mevcut olmadığında zorunludur.',
    'required_without_all' => ':attribute alanı, :values hiçbirisi mevcut olmadığında zorunludur.',
    'same'                 => ':attribute ile :other eşleşmelidir.',
    'size'                 => [
        'array'   => ':attribute, :size öğe içermelidir.',
        'file'    => ':attribute, :size kilobayt olmalıdır.',
        'numeric' => ':attribute, :size olmalıdır.',
        'string'  => ':attribute, :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şu değerlerden biriyle başlamalıdır: :values.',
    'string'      => ':attribute bir metin olmalıdır.',
    'timezone'    => ':attribute geçerli bir zaman dilimi olmalıdır.',
    'unique'      => ':attribute zaten alınmış.',
    'uploaded'    => ':attribute yüklenemedi.',
    'url'         => ':attribute geçerli bir URL olmalıdır.',
    'uuid'        => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Burada, "attribute.rule" konvansiyonunu kullanarak belirli doğrulama
    | kuralları için özelleştirilmiş doğrulama mesajları belirtebilirsiniz.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'özelleştirilmiş mesaj',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Doğrulama Alanları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, "email" yerine "E-Posta Adresi" gibi daha okunabilir
    | hale getirmek için, placeholder olarak kullanılan alanları değiştirir.
    |
    */

    'attributes' => [],

];
